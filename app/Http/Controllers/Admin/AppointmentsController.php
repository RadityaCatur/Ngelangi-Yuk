<?php

namespace App\Http\Controllers\Admin;

use App\Appointment;
use App\Client;
use App\Employee;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAppointmentRequest;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Role;
use App\Service;
use Gate;
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
    
            // Placeholder pakai closure
            $table->addColumn('placeholder', function () {
                return '&nbsp;';
            });
    
            // Actions pakai closure
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
    
            // Kolom lainnya
            $table->editColumn('id', function ($row) {
                return $row->id ?? '';
            });
    
            $table->addColumn('clients_name', function ($row) {
                return optional($row->client)->name ?? '';
            });
    
            $table->addColumn('employee_name', function ($row) {
                return optional($row->employee)->name ?? '';
            });
    
            $table->editColumn('price', function ($row) {
                return $row->price ?? '';
            });
    
            $table->editColumn('comments', function ($row) {
                return $row->comments ?? '';
            });
    
            $table->editColumn('services', function ($row) {
                $categories = $row->services->pluck('category')->unique();
                $labels = [];
    
                foreach ($categories as $category) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $category);
                }
    
                return implode('<br>', $labels);
            });
    
            // Kolom HTML jangan di-escape
            $table->rawColumns(['actions', 'placeholder', 'services']);
    
            return $table->make(true);
        }
    
        return view('admin.appointments.index');
    }

    public function create()
    {
        abort_if(Gate::denies('appointment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clients = Client::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $employees = Employee::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $services = Service::all()
        ->groupBy('category')
        ->map(function ($items, $category) {
            return $items->first(); // ambil salah satu service dari setiap kategori
        });

        return view('admin.appointments.create', compact('clients', 'employees', 'services'));
    }

    public function store(StoreAppointmentRequest $request)
    {
        $appointment = Appointment::create($request->all());
        $appointment->services()->sync($request->input('services', []));

        return redirect()->route('admin.appointments.index');
    }

    public function edit(Appointment $appointment)
    {
        abort_if(Gate::denies('appointment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = auth()->user();
        $role = $user->roles()->first()?->title;

        if ($role === 'Murid') {
            $client = Client::where('user_id', $user->id)->first();

            // Jika appointment sudah ada client lain dan bukan dirinya
            if ($appointment->client_id && $appointment->client_id !== $client?->id) {
                return abort(403, 'Jadwal ini sudah diambil oleh murid lain.');
            }
        }

        // Ambil data pelatih (employee) berdasarkan user_id jika login sebagai pelatih
        if ($role === 'Pelatih') {
            $employee = Employee::where('user_id', $user->id)->first();

            // Hanya dirinya sendiri yang muncul sebagai opsi
            $employees = collect([$employee->id => $employee->name]);
        } else {
            // Jika admin, munculkan semua pelatih
            $employees = Employee::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        }

        // Biarkan client list seperti biasa (akan difokuskan di poin berikutnya)
        $clients = Client::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $services = Service::all()
        ->groupBy('category')
        ->map(function ($items, $category) {
            return $items->first(); // ambil salah satu service dari setiap kategori
        });

        $appointment->load('client', 'employee', 'services');

        return view('admin.appointments.edit', compact('clients', 'employees', 'services', 'appointment'));
    }

    public function join(Appointment $appointment)
    {
        $user = auth()->user();
        $client = Client::where('user_id', $user->id)->first();

        if (!$client) {
            return back()->withErrors(['join' => 'Client tidak ditemukan.']);
        }

        // Jika sudah terisi oleh client lain
        if ($appointment->client_id && $appointment->client_id !== $client->id) {
            return back()->withErrors(['join' => 'Sesi ini sudah diisi oleh murid lain.']);
        }

        // Jika sudah terdaftar
        if ($appointment->client_id === $client->id) {
            return back()->with('message', 'Kamu sudah terdaftar di sesi ini.');
        }

        // Cek kuota
        if ($client->kuota <= 0) {
            return back()->withErrors(['join' => 'Kuota kamu sudah habis.']);
        }

        // Proses pendaftaran
        $appointment->update([
            'client_id' => $client->id
        ]);

        $client->decrement('kuota');

        return redirect()->route('admin.systemCalendar.details', ['date' => $appointment->start_time->format('Y-m-d')])
            ->with('message', 'Berhasil mendaftar ke sesi latihan.');
    }


    public function leave(Appointment $appointment)
    {
        $user = auth()->user();
        $client = Client::where('user_id', $user->id)->first();

        if (!$client) {
            return back()->withErrors(['cancel' => 'Client tidak ditemukan.']);
        }

        if ($appointment->client_id !== $client->id) {
            return back()->withErrors(['cancel' => 'Kamu belum terdaftar di appointment ini.']);
        }

        $start = \Carbon\Carbon::parse($appointment->start_time);
        $now = \Carbon\Carbon::now();

        // ⛔ Tidak bisa batal karena kurang dari 12 jam
        if ($start->diffInRealHours($now) < 12) {
            return back()->withErrors(['cancel' => 'Pembatalan hanya bisa dilakukan lebih dari 12 jam sebelum waktu mulai.']);
        }

        // ✅ Bisa batal
        $appointment->update(['client_id' => null]);
        $client->increment('kuota');

        return redirect()->route('admin.systemCalendar')
            ->with('status', 'Berhasil membatalkan appointment.');
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
{
    $user = auth()->user();
    $role = $user->roles()->first()?->title;

    $appointment->services()->sync($request->input('services', []));

    if ($role === 'Admin') {
        // Admin bisa ubah semua data termasuk client_id
        $appointment->update($request->all());

    } elseif ($role === 'Murid') {
        $client = Client::where('user_id', $user->id)->first();

        if (!$client) {
            return back()->withErrors(['client' => 'Client tidak ditemukan.']);
        }

        $clientId = $client->id;

        if ($appointment->client_id !== $clientId && $appointment->client_id === null) {
            // Belum ada client, dan client ingin mendaftar
            if ($client->kuota > 0) {
                $appointment->update([
                    'client_id' => $clientId
                ]);
                $client->decrement('kuota');
            } else {
                return back()->withErrors(['kuota' => 'Kuota kamu sudah habis']);
            }

        } elseif ($appointment->client_id === $clientId && $request->input('client_id') != $clientId) {
            // Client ingin membatalkan
            $start = \Carbon\Carbon::parse($appointment->start_time);
            $now = \Carbon\Carbon::now();

            if ($now->lt($start) && $start->diffInRealHours($now) >= 12) {
                $appointment->update([
                    'client_id' => null
                ]);
                $client->increment('kuota');
            } else {
                return back()->withErrors(['cancel' => 'Pembatalan hanya bisa dilakukan > 12 jam sebelum mulai.']);
            }
        }

        // Catatan: jika client_id tetap sama dan tidak berubah, tidak perlu update apa pun
    }

    return redirect()->route('admin.systemCalendar')
        ->with('message', 'Appointment berhasil diperbarui.');
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
