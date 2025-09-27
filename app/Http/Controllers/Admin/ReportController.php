<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

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
                                ->orderBy('start_time', 'desc')
                                ->paginate(5); // <-- Kita tampilkan 5 report per halaman

        return view('admin.clients.reports', compact('appointments'));
    }
}
