<?php

namespace Database\Seeders;

use App\Models\SmsTemplate;
use Illuminate\Database\Seeder;

class SmsTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            [
                'name' => 'Expiry Reminder - 7 days',
                'days_before' => 7,
                'message_body' => 'Hello {username}, your service expires on {expiry_date} ({days_left} days left). - {company_name}',
            ],
            [
                'name' => 'Expiry Reminder - 3 days',
                'days_before' => 3,
                'message_body' => 'Reminder: {username}, your service expires on {expiry_date}. {days_left} days left. - {company_name}',
            ],
            [
                'name' => 'Expiry Reminder - 1 day',
                'days_before' => 1,
                'message_body' => 'Urgent: {username}, your service expires on {expiry_date}. - {company_name}',
            ],
        ];

        foreach ($defaults as $template) {
            SmsTemplate::query()->updateOrCreate([
                'days_before' => $template['days_before'],
            ], [
                'name' => $template['name'],
                'trigger_type' => 'days_before_expiry',
                'message_body' => $template['message_body'],
                'is_active' => true,
            ]);
        }
    }
}
