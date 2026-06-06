<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UpdateAppointmentReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();
        $appointment = $this->route('appointment');

        if (! $user || ! $appointment) {
            return false;
        }

        // Cek role 'pelatih' (case-insensitive)
        $isPelatih = DB::table('role_user')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('role_user.user_id', $user->id)
            ->whereRaw('LOWER(roles.title) = ?', ['pelatih'])
            ->exists();

        // Cek role admin (opsional)
        $isAdmin = DB::table('role_user')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('role_user.user_id', $user->id)
            ->whereRaw('LOWER(roles.title) = ?', ['admin'])
            ->exists();

        // Hanya pelatih yang ditugaskan atau admin boleh submit
        return ($isPelatih && ($user->employee?->id == $appointment->employee_id)) || $isAdmin;
    }

    public function rules(): array
    {
        return [
            'comments' => ['required', 'string', 'min:10'],
        ];
    }
}
