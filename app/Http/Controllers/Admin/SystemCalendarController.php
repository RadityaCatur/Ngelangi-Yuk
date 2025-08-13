<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Appointment;
use App\Client;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class SystemCalendarController extends Controller
{
    public function index()
    {
        $user = Auth::user();
    $isClient = $user->roles()->where('title', 'Murid')->exists();

    if ($isClient) {
        $client = Client::where('user_id', $user->id)->with('services')->first();
        $allowedCategories = $client->categoryList();

        $appointments = Appointment::with(['client', 'employee', 'services'])->get();
        $appointments = $appointments->filter(function ($appointment) use ($allowedCategories) {
            return $appointment->services->contains(function ($service) use ($allowedCategories) {
                return $allowedCategories->contains($service->category);
            });
        });
    } else {
        $appointments = Appointment::with(['client', 'employee', 'services'])->get();
    }

    // Group by tanggal (format: Y-m-d)
    $grouped = $appointments->groupBy(function ($appointment) {
        return $appointment->start_time->format('Y-m-d');
    });

    $events = [];

    foreach ($grouped as $date => $appointmentsOnDate) {
        $total = $appointmentsOnDate->count();
        $available = $appointmentsOnDate->whereNull('client_id')->count();

        $events[] = [
            'title' => $total . ' Sesi Latihan',
            'start' => $date,
            'allDay' => true,
            'url' => route('admin.systemCalendar.details', ['date' => $date]),
            'className' => $available == 0 ? 'event-filled' : 'event-empty',
        ];
    }

    return view('admin.calendar.calendar', compact('events'));
    }

    public function showDateDetails($date)
    {
        Carbon::setLocale('id');
        
        $user = Auth::user();
        $isClient = $user->roles()->where('title', 'Murid')->exists();

        $client = null;

        if ($isClient) {
            $client = Client::where('user_id', $user->id)->with('services')->first();
            $allowedCategories = $client->categoryList();

            $appointments = Appointment::with(['client', 'employee.user', 'services'])
                ->whereDate('start_time', $date)
                ->get()
                ->filter(function ($appointment) use ($allowedCategories) {
                    return $appointment->services->contains(function ($service) use ($allowedCategories) {
                        return $allowedCategories->contains($service->category);
                    });
                });
        } else {
            $appointments = Appointment::with(['client', 'employee.user', 'services'])
                ->whereDate('start_time', $date)
                ->get();
        }

        $appointments = $appointments->sortBy(function ($appointment) {
            return [$appointment->start_time, $appointment->finish_time];
        });

        return view('admin.calendar.details', compact('appointments', 'date', 'client'));
    }
}