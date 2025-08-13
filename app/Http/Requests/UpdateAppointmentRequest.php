<?php

namespace App\Http\Requests;

use App\Appointment;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('appointment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'client_id'   => [
                'nullable',
                'integer',
            ],
            'start_time'  => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'finish_time' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'services.*'  => [
                'integer',
            ],
            'services'    => [
                'array',
            ],
        ];
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        // Periksa apakah user adalah admin
        if (!auth()->user()->hasRole('admin')) {
            // Jika appointment sudah memiliki employee_id dan user bukan admin, batalkan update
            if ($appointment->employee_id && $appointment->employee_id !== auth()->user()->employee->id) {
                return redirect()->back()->withErrors('Anda tidak memiliki izin untuk mengubah appointment ini.');
            }
        }

        // Lanjutkan proses update
        $appointment->update($request->all());
        $appointment->services()->sync($request->input('services', []));

        return redirect()->route('admin.appointments.index');
    }
}
