<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Dapatkan appointment yang sedang diakses dari rute
        $appointment = $this->route('appointment');

        // Izinkan HANYA jika user yang login adalah 'Pelatih' DAN
        // ID pelatih tersebut sama dengan ID pelatih di jadwal ini.
        return auth()->user()->roles()->where('title', 'Pelatih')->exists() &&
               auth()->user()->employee?->id === $appointment->employee_id;
    }

    public function rules(): array
    {
        // Aturan validasi untuk laporan
        return [
            'comments' => ['required', 'string', 'min:10'],
        ];
    }
}