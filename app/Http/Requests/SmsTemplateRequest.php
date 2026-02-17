<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SmsTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'trigger_type' => ['required', 'in:days_before_expiry'],
            'days_before' => ['required', 'integer', 'min:0'],
            'message_body' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
