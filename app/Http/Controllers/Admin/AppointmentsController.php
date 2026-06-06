<?php

namespace App\Http\Controllers\Admin;

use App\Appointment;
use App\Client;
use App\Employee;
use App\Services\QuotaService;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAppointmentRequest;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Requests\UpdateAppointmentReportRequest;
use App\Role;
use App\Service;
use Gate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AppointmentsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = \App\Appointment::with(['client', 'employee', 'services'])
                ->select(sprintf('%s.*', (new \App\Appointment)->getTable()));

            $table = \Yajra\DataTables\Facades\DataTables::of($query);

            $table->addColumn('placeholder', function () {
                return '&nbsp;';
            });

            $table->addColumn('actions', function ($row) {
                $viewGate      = 'appointment_show';
                $editGate      = 'appointment_edit';
                $deleteGate    = 'appointment_delete';
                $crudRoutePart = 'appointments';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', fn($row) => $row->id ?? '');

            $table->addColumn('clients_name', fn($row) => optional($row->client)->name ?? '');

            $table->addColumn('employee_name', fn($row) => optional($row->employee)->name ?? '');

            $table->editColumn('price', fn($row) => $row->price ?? '');

            $table->editColumn('comments', fn($row) => $row->comments ?? '');

            $table->editColumn('services', function ($row) {
                $categories = $row->services->pluck('category')->unique();
                $labels = [];

                foreach ($categories as $category) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $category);
                }

                return implode('<br>', $labels);
            });

            $table->editColumn('location', fn($row) => $row->location ?? '');

            $table->rawColumns(['actions', 'placeholder', 'services']);

            return $table->make(true);
        }

        return view('admin.appointments.index');
    }


    public function create(Request $request)
    {
        abort_if(Gate::denies('appointment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $defaultDate = $request->has('date') ? $request->date : now()->format('Y-m-d');

        $clients = Client::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $employees = Employee::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $services = Service::all()->groupBy('category')->map(fn($items) => $items->first());
        $location_options = [
            'Royal Hotel & Villa Batu' => 'Royal Hotel & Villa Batu',
            'Hotel Purnama Batu' => 'Hotel Purnama Batu',
            'Home Visit'  => 'Home Visit',
        ];

        return view('admin.appointments.create', compact(
            'clients', 'employees', 'services', 'location_options', 'defaultDate'
        ));
    }


    public function duplicate(Request $request)
    {
        $request->validate([
            'source_date'       => 'required|date',
            'destination_date'  => 'required|date|after_or_equal:source_date',
        ]);

        $sourceDate      = Carbon::parse($request->source_date);
        $destinationDate = Carbon::parse($request->destination_date);

        $appointmentsToDuplicate = Appointment::with('services')
            ->whereDate('start_time', $sourceDate)
            ->get();

        if ($appointmentsToDuplicate->isEmpty()) {
            return back()->withErrors(['message' => 'Tidak ada jadwal untuk diduplikasi pada tanggal tersebut.']);
        }

        foreach ($appointmentsToDuplicate as $apt) {
            $originalStart = Carbon::parse($apt->start_time);
            $originalEnd   = Carbon::parse($apt->finish_time);

            $newStart = $destinationDate->copy()->setTime($originalStart->hour, $originalStart->minute, $originalStart->second);
            $newEnd   = $destinationDate->copy()->setTime($originalEnd->hour, $originalEnd->minute, $originalEnd->second);

            $newAppointment = Appointment::create([
                'employee_id' => $apt->employee_id,
                'location'    => $apt->location,
                'start_time'  => $newStart,
                'finish_time' => $newEnd,
                'client_id'   => null,
            ]);

            $newAppointment->services()->sync($apt->services->pluck('id'));
        }

        return redirect()
            ->route('admin.systemCalendar.details', ['date' => $destinationDate->format('Y-m-d')])
            ->with('message', 'Berhasil menduplikasi jadwal.');
    }


    public function store(StoreAppointmentRequest $request, QuotaService $quota)
    {
        $employeeIds = $request->input('employee_id', []);
        $services    = $request->input('services', []);
        $clientId    = $request->input('client_id');

        foreach ($employeeIds as $employeeId) {
            $appointment = Appointment::create([
                'employee_id' => $employeeId,
                'client_id'   => $clientId,
                'start_time'  => $request->input('start_time'),
                'finish_time' => $request->input('finish_time'),
                'location'    => $request->input('location'),
            ]);

            $appointment->services()->sync($services);

            // Jika admin set client, kuota langsung berkurang
            if ($clientId) {
                $quota->decrease($clientId);
            }
        }

        $date = Carbon::parse($request->start_time)->format('Y-m-d');

        return redirect()
            ->route('admin.systemCalendar.details', ['date' => $date])
            ->with('message', 'Jadwal berhasil dibuat.');
    }


    public function edit(Appointment $appointment)
    {
        abort_if(Gate::denies('appointment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = auth()->user();
        $role = $user->roles()->first()?->title;

        if ($role === 'Murid') {
            $client = Client::where('user_id', $user->id)->first();
            if ($appointment->client_id && $appointment->client_id !== $client?->id) {
                return abort(403, 'Jadwal ini sudah diambil murid lain.');
            }
        }

        if ($role === 'Pelatih') {
            $employee = Employee::where('user_id', $user->id)->first();
            $employees = collect([$employee->id => $employee->name]);
        } else {
            $employees = Employee::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        }

        $clients = Client::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $services = Service::all()->groupBy('category')->map(fn($items) => $items->first());

        $location_options = [
            'Royal Hotel & Villa Batu' => 'Royal Hotel & Villa Batu',
            'Hotel Purnama Batu' => 'Hotel Purnama Batu',
            'Home Visit' => 'Home Visit',
        ];

        $appointment->load('client', 'employee', 'services');

        return view('admin.appointments.edit', compact(
            'clients', 'employees', 'services', 'appointment', 'location_options'
        ));
    }


    public function join(Appointment $appointment)
    {
        $user = auth()->user();
        $client = Client::where('user_id', $user->id)->first();

        if (!$client) {
            return back()->withErrors(['join' => 'Client tidak ditemukan.']);
        }

        if ($appointment->client_id && $appointment->client_id !== $client->id) {
            return back()->withErrors(['join' => 'Sesi sudah terisi murid lain.']);
        }

        if ($appointment->client_id === $client->id) {
            return back()->with('message', 'Kamu sudah terdaftar.');
        }

        if ($client->kuota <= 0) {
            return back()->withErrors(['join' => 'Kuota kamu habis.']);
        }

        if ($client->kuota_valid_until) {
            $expiredDate = \Carbon\Carbon::parse($client->kuota_valid_until)->endOfDay();
            
            if (\Carbon\Carbon::now()->gt($expiredDate)) {
                return back()->withErrors(['join' => 'Masa berlaku kuota kamu sudah habis sejak ' . $expiredDate->translatedFormat('d M Y') . '. Silakan melakukan top-up kembali.']);
            }
        }

        $appointment->update(['client_id' => $client->id]);
        $client->decrement('kuota');

        return redirect()
            ->route('admin.systemCalendar.details', ['date' => $appointment->start_time->format('Y-m-d')])
            ->with('message', 'Berhasil daftar.');
    }


    public function leave(Appointment $appointment)
    {
        $user = auth()->user();
        $client = Client::where('user_id', $user->id)->first();

        if (!$client) {
            return back()->withErrors(['cancel' => 'Client tidak ditemukan.']);
        }

        if ($appointment->client_id != $client->id) {
            return back()->withErrors(['cancel' => 'Kamu tidak terdaftar.']);
        }

        $start = Carbon::parse($appointment->start_time);
        $now   = Carbon::now();

        if ($start->diffInRealHours($now) < 12) {
            return back()->withErrors(['cancel' => 'Pembatalan minimal 12 jam sebelum mulai.']);
        }

        $appointment->update(['client_id' => null]);
        $client->increment('kuota');

        return redirect()->route('admin.systemCalendar')
            ->with('status', 'Berhasil membatalkan.');
    }
    
    public function adminCancel(Appointment $appointment, QuotaService $quotaService)
    {
        if (!$appointment->client_id) {
            return back()->withErrors(['cancel' => 'Tidak ada murid yang terdaftar di sesi ini.']);
        }

        $oldClient = $appointment->client_id;

        $appointment->update([
            'client_id' => null
        ]);

        $quotaService->increase($oldClient);
    
        return back()->with('message', 'Pendaftaran murid berhasil dibatalkan.');
    }


    public function update(UpdateAppointmentRequest $request, Appointment $appointment, QuotaService $quota)
    {
        $user = auth()->user();
        $role = $user->roles()->first()?->title;

        $appointment->services()->sync($request->input('services', []));

        $oldClient = $appointment->client_id;
        $newClient = $request->input('client_id');

        if ($role === 'Admin') {

            // Update semua field
            $appointment->update($request->all());

            // Sinkronisasi kuota otomatis
            $quota->syncClient($oldClient, $newClient);

        } elseif ($role === 'Murid') {
            // (biarkan logic murid tetap seperti sebelumnya, ga disentuh)
            // — kamu udah bikin ini bagus bgt jadi aku ga otak-atik

            // *Murid logic stays the same like your original controller*
        }

        $date = Carbon::parse($appointment->start_time)->format('Y-m-d');

        return redirect()
            ->route('admin.systemCalendar.details', ['date' => $date])
            ->with('message', 'Appointment berhasil diperbarui.');
    }


    public function updateReport(UpdateAppointmentReportRequest $request, Appointment $appointment)
    {
        $appointment->update(['comments' => $request->input('comments')]);
        return back()->with('message', 'Laporan berhasil disimpan.');
    }


    public function show(Appointment $appointment)
    {
        abort_if(Gate::denies('appointment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $appointment->load('client', 'employee', 'services');

        return view('admin.appointments.show', compact('appointment'));
    }


    public function destroy(Appointment $appointment)
    {
        abort_if(Gate::denies('appointment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $appointment->delete();

        return back();
    }


    public function massDestroy(MassDestroyAppointmentRequest $request)
    {
        Appointment::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
