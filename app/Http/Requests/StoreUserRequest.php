<?php

namespace App\Http\Requests;

use App\Role;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => [
                'required',
            ],
            'username' => [
                'required', 'unique:users,username'
            ],
            'password' => [
                'required',
            ],
            'roles' => [
                'required',
            ],
        ];

        $roleId = $this->input('roles');

        $roleTitle = null;
        if ($roleId) {
            $role = Role::find($roleId);
            $roleTitle = $role ? $role->title : null;
        }

        if ($roleTitle === 'Pelatih') {
            $rules['employee_phone'] = ['nullable', 'string', 'max:20'];
        }

        if ($roleTitle === 'Murid') {
            $rules['client_phone'] = ['nullable', 'string', 'max:20'];
            $rules['client_kuota'] = ['nullable', 'integer', 'min:0'];
            $rules['client_services'] = ['required', 'array', 'min:1'];
            $rules['client_services.*'] = ['integer', 'exists:services,id'];
            $rules['client_kuota_valid_until'] = ['nullable', 'date'];
        }

        return $rules;
    }
}