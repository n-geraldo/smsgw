<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DmaMappingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'table' => ['required', 'string', 'regex:/^[A-Za-z0-9_]+$/'],
            'username_column' => ['required', 'string', 'regex:/^[A-Za-z0-9_]+$/'],
            'phone_column' => ['required', 'string', 'regex:/^[A-Za-z0-9_]+$/'],
            'expiration_column' => ['required', 'string', 'regex:/^[A-Za-z0-9_]+$/'],
            'status_column' => ['nullable', 'string', 'regex:/^[A-Za-z0-9_]+$/'],
            'status_active_values' => ['nullable', 'string'],
        ];
    }
}
