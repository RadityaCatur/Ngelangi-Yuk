<?php

namespace App\Http\Controllers\Admin;

use App\Appointment;
use App\Employee;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TrainingHoursController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $role = $user->roles()->first()?->title;

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->endOfMonth()->toDateString());

        if ($role === 'Admin') {
            return $this->adminView($startDate, $endDate);
        }

        if ($role === 'Pelatih') {
            return $this->trainerView($user, $startDate, $endDate);
        }

        abort(403);
    }

    private function adminView($startDate, $endDate)
    {
        // Tambahkan 'client' di dalam with() agar bisa mengambil nama murid
        $appointments = Appointment::with(['employee', 'client'])
            ->whereNotNull('comments') // VALID SESSION
            ->whereBetween('start_time', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->orderBy('start_time') // Urutkan berdasarkan waktu
            ->get();

        $result = [];

        foreach ($appointments as $apt) {
            if (!$apt->employee) continue;

            $minutes = Carbon::parse($apt->start_time)
                ->diffInMinutes(Carbon::parse($apt->finish_time));

            $employeeId = $apt->employee->id;
            $date = Carbon::parse($apt->start_time)->format('Y-m-d');

            // Set up data pelatih jika belum ada
            if (!isset($result[$employeeId])) {
                $result[$employeeId] = [
                    'name' => $apt->employee->name,
                    'total_minutes' => 0,
                    'perDay' => [], // Siapkan array untuk detail harian
                ];
            }

            // Tambah total menit keseluruhan pelatih
            $result[$employeeId]['total_minutes'] += $minutes;

            // Set up data harian pelatih jika belum ada
            if (!isset($result[$employeeId]['perDay'][$date])) {
                $result[$employeeId]['perDay'][$date] = [
                    'minutes' => 0,
                    'clients' => [],
                ];
            }

            // Tambah menit harian
            $result[$employeeId]['perDay'][$date]['minutes'] += $minutes;

            // Masukkan nama murid tanpa duplikat
            if ($apt->client) {
                $clientName = $apt->client->name;
                if (!in_array($clientName, $result[$employeeId]['perDay'][$date]['clients'])) {
                    $result[$employeeId]['perDay'][$date]['clients'][] = $clientName;
                }
            }
        }

        return view('admin.employees.hours', [
            'mode' => 'admin',
            'data' => $result,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    private function trainerView($user, $startDate, $endDate)
    {
        $employee = Employee::where('user_id', $user->id)->firstOrFail();
    
        $appointments = Appointment::with('client')
            ->where('employee_id', $employee->id)
            ->whereNotNull('comments')
            ->whereBetween('start_time', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->orderBy('start_time') // penting buat urutan murid
            ->get();
    
        $totalMinutes = 0;
        $perDay = [];
    
        foreach ($appointments as $apt) {
            $minutes = Carbon::parse($apt->start_time)
                ->diffInMinutes(Carbon::parse($apt->finish_time));
    
            $date = Carbon::parse($apt->start_time)->format('Y-m-d');
    
            if (!isset($perDay[$date])) {
                $perDay[$date] = [
                    'minutes' => 0,
                    'clients' => [],
                ];
            }
    
            $perDay[$date]['minutes'] += $minutes;
            $totalMinutes += $minutes;
    
            // merge client (tanpa duplikat)
            if ($apt->client) {
                $clientName = $apt->client->name;
                if (!in_array($clientName, $perDay[$date]['clients'])) {
                    $perDay[$date]['clients'][] = $clientName;
                }
            }
        }
    
        return view('admin.employees.hours', [
            'mode' => 'trainer',
            'totalMinutes' => $totalMinutes,
            'perDay' => $perDay,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
