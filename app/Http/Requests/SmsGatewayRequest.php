<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SmsGatewayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:150'],
            'base_url' => ['required', 'string', 'max:255'],
            'endpoint_path' => ['nullable', 'string', 'max:255'],
            'auth_type' => ['required', 'in:none,basic,bearer'],
            'username' => ['nullable', 'string', 'required_if:auth_type,basic'],
            'password' => ['nullable', 'string', 'required_if:auth_type,basic'],
            'token' => ['nullable', 'string', 'required_if:auth_type,bearer'],
            'sender_id' => ['nullable', 'string', 'max:50'],
            'request_type' => ['required', 'in:json,query'],
            'is_active' => ['nullable', 'boolean'],
            'is_default' => ['nullable', 'boolean'],
        ];

        if ($this->route('gateway')) {
            $rules['username'] = ['nullable', 'string'];
            $rules['password'] = ['nullable', 'string'];
            $rules['token'] = ['nullable', 'string'];
        }

        return $rules;
    }
}
