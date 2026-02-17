<?php

namespace App\Services;

use Carbon\Carbon;

class TemplateRenderer
{
    public function render(string $template, array $data): string
    {
        $expiryDate = $data['expiration_date'] ?? null;
        $expiryDate = $expiryDate instanceof Carbon ? $expiryDate : ($expiryDate ? Carbon::parse($expiryDate) : null);
        $today = Carbon::today();

        $placeholders = [
            '{username}' => (string) ($data['username'] ?? ''),
            '{expiry_date}' => $expiryDate ? $expiryDate->format('Y-m-d') : '',
            '{days_left}' => $expiryDate ? max(0, $today->diffInDays($expiryDate, false)) : '',
            '{company_name}' => (string) config('sms.company_name', ''),
        ];

        return str_replace(array_keys($placeholders), array_values($placeholders), $template);
    }
}
