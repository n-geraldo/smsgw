<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SmsTestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'max:30'],
            'template_id' => ['required', 'exists:sms_templates,id'],
            'gateway_id' => ['nullable', 'exists:sms_gateways,id'],
        ];
    }
}
