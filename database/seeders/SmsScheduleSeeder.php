<?php

namespace Database\Seeders;

use App\Models\SmsSchedule;
use App\Models\SmsTemplate;
use Illuminate\Database\Seeder;

class SmsScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $timezone = config('app.timezone', 'UTC');

        SmsTemplate::query()->get()->each(function (SmsTemplate $template) use ($timezone) {
            SmsSchedule::query()->firstOrCreate([
                'template_id' => $template->id,
            ], [
                'run_hour' => 9,
                'timezone' => $timezone,
                'is_active' => true,
            ]);
        });
    }
}
