<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class HomeController
{
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.systemCalendar');
    }
}
