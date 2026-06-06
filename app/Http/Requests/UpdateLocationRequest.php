<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;

class UpdateLocationRequest extends FormRequest
{
    public function authorize()
    {
        return true; // We'll handle authorization in the controller
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'URL' => [
                'nullable',
                'string',
            ],
        ];
    }
}
