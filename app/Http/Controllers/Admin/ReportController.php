<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Pastikan user memiliki profil sebagai client/murid
        $client = Client::where('user_id', $user->id)->first();
        if (!$client) {
            // Redirect atau tampilkan pesan error jika bukan murid
            return redirect()->route('admin.home')->with('error', 'Anda tidak memiliki akses ke halaman report.');
        }

        // Ambil semua jadwal latihan milik murid tersebut
        // Urutkan dari yang paling baru, dan gunakan pagination
        $appointments = $client->appointments()
            ->where('finish_time', '<', Carbon::now())
            ->orderBy('start_time', 'desc')
            ->paginate(5);

        return view('admin.clients.reports', compact('appointments'));
    }
    
    public function showClientReports(Client $client)
    {
        // Cuma Admin yang boleh akses
        if (auth()->user()->roles()->where('title', 'Admin')->doesntExist()) {
            abort(403, 'Kamu tidak punya izin untuk mengakses laporan ini.');
        }
    
        // Ambil appointment milik murid tersebut
        $appointments = $client->appointments()
            ->where('finish_time', '<', Carbon::now())
            ->orderBy('start_time', 'desc')
            ->paginate(5);
    
        return view('admin.clients.reports', compact('appointments', 'client'));
    }
    
    public function showFromAppointment($appointmentId)
    {
        $appointment = \App\Appointment::with('client')->findOrFail($appointmentId);
    
        if (!$appointment->client_id) {
            return redirect()->back()->with('error', 'Appointment ini belum memiliki murid.');
        }
    
        $client = $appointment->client;
    
        // Ambil semua laporan murid terkait
        $appointments = $client->appointments()
            ->orderBy('start_time', 'desc')
            ->paginate(5);
    
        return view('admin.clients.reports', compact('appointments', 'client'));
    }
}
