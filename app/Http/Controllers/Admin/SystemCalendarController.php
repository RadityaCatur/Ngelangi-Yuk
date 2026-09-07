<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // <-- Tambahkan ini
use Illuminate\Support\Facades\Auth;
use App\Appointment;
use App\Client;
use App\Employee;
use Carbon\Carbon;

class SystemCalendarController extends Controller
{
    public function index(Request $request) // <-- Tambahkan Request
    {
        $user = Auth::user();
        
        // 1. Mulai query builder, BUKAN langsung ->get()
        $query = Appointment::query();

        // 2. Terapkan filter berdasarkan role
        if ($user->hasRole('Murid')) {
            $client = Client::where('user_id', $user->id)->first();
            if ($client) {
                // Asumsi: categoryList() mengembalikan array kata kunci kategori,
                // misal: ['Anak', 'Dewasa', 'Therapy']
                $allowedKeywords = $client->categoryList();

                // Menggunakan whereHas untuk memfilter berdasarkan KATA KUNCI di kolom NAMA service
                $query->whereHas('services', function ($q) use ($allowedKeywords) {
                    $q->where(function ($query) use ($allowedKeywords) {
                        // Iterasi setiap kata kunci untuk membangun query OR
                        foreach ($allowedKeywords as $keyword) {
                            // Cari service yang namanya MENGANDUNG kata kunci kategori
                            $query->orWhere('name', 'LIKE', '%' . $keyword . '%');
                        }
                    });
                });
            }
        } elseif ($user->hasRole('Pelatih')) {
            // LOGIKA BARU: Filter untuk Pelatih
            if ($request->query('view') === 'mine') {
                $employee = $user->employee;
                if ($employee) {
                    $query->where('employee_id', $employee->id);
                }
            }
            // Jika tidak ada ?view=mine, tidak ada filter tambahan, jadi pelatih melihat semua.
        }
        // Admin tidak perlu filter, jadi query tetap mengambil semua data.

        // 3. Ambil data SETELAH semua filter diterapkan
        $appointments = $query->with('client')->get();

        // 4. Logika unik Anda untuk grouping dan event tetap dipertahankan
        $grouped = $appointments->groupBy(function ($appointment) {
            return $appointment->start_time->format('Y-m-d');
        });

        $events = [];
        foreach ($grouped as $date => $appointmentsOnDate) {
            $total = $appointmentsOnDate->count();
            $available = $appointmentsOnDate->whereNull('client_id')->count();

            $events[] = [
                'title'     => $total . ' Sesi Latihan',
                'start'     => $date,
                'allDay'    => true,
                'url'       => route('admin.systemCalendar.details', [
                    'date' => $date,
                    'view' => $request->query('view') // Teruskan parameter 'view' ke halaman detail
                ]),
                'className' => $available == 0 ? 'event-filled' : 'event-empty',
            ];
        }

        return view('admin.calendar.calendar', compact('events'));
    }

    public function showDateDetails(Request $request, $date) // <-- Tambahkan Request
    {
        Carbon::setLocale('id');
        $user = Auth::user();
        $client = null;

        // 1. Mulai query builder dengan filter tanggal awal
        $query = Appointment::whereDate('start_time', $date);

        // 2. Terapkan filter berdasarkan role (logika yang sama persis seperti di index)
        if ($user->hasRole('Murid')) {
            $client = Client::where('user_id', $user->id)->first();
            if ($client) {
                $allowedCategories = $client->categoryList();
                $query->whereHas('services', function ($q) use ($allowedCategories) {
                    // Membangun sub-query WHERE yang mencari kata kunci di kolom 'name'
                    $q->where(function ($query) use ($allowedCategories) {
                        foreach ($allowedCategories as $keyword) {
                        $query->orWhere('name', 'LIKE', '%' . $keyword . '%');
                        }
                    });
                });
            }
        } elseif ($user->hasRole('Pelatih')) {
            // LOGIKA BARU: Filter untuk Pelatih
            if ($request->query('view') === 'mine') {
                $employee = $user->employee;
                if ($employee) {
                    $query->where('employee_id', $employee->id);
                }
            }
        }
        
        // 3. Ambil data SETELAH semua filter diterapkan
        $appointments = $query->with(['client', 'employee.user', 'services', 'locationRelation'])->get();

        // 4. Logika sorting Anda tetap dipertahankan
        $appointments = $appointments->sortBy(function ($appointment) {
            return [$appointment->start_time, $appointment->finish_time];
        });

        $employees = Employee::orderBy('name')->pluck('name', 'id');

        return view('admin.calendar.details', compact('appointments', 'date', 'client', 'employees'));
    }
}