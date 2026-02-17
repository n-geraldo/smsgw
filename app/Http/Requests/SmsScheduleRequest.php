<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SmsScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'template_id' => ['required', 'exists:sms_templates,id'],
            'run_hour' => ['required', 'integer', 'min:0', 'max:23'],
            'timezone' => ['required', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
