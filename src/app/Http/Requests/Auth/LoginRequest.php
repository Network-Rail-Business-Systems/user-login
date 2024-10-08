<?php

namespace NetworkRailBusinessSystems\UserLogin\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                'between:1,191',
            ],
            'password' => [
                'required',
                'string',
                'between:1,191',
            ],
            'remember' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
