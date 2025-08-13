<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Client;

class TopUpController extends Controller
{
    public function showTopUpPage()
    {
        $user = Auth::user();
        $client = Client::where('user_id', $user->id)->firstOrFail();

        return view('admin.clients.topup', compact('client'));
    }
}