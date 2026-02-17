<?php

namespace App\Services;

use App\Jobs\SendSmsJob;
use App\Models\CustomerCache;
use App\Models\SmsJob;
use App\Models\SmsSchedule;
use Carbon\Carbon;

class ExpirationScanner
{
    public function __construct(protected DmaRepository $dma) {}

    public function scan(): array
    {
        $now = Carbon::now();
        $created = 0;
        $skipped = 0;

        $schedules = SmsSchedule::query()
            ->with('template')
            ->where('is_active', true)
            ->get();

        foreach ($schedules as $schedule) {
            $template = $schedule->template;

            if (! $template || ! $template->is_active) {
                continue;
            }

            $timezone = $schedule->timezone ?: config('app.timezone');
            $nowTz = $now->copy()->setTimezone($timezone);

            if ((int) $nowTz->hour !== (int) $schedule->run_hour) {
                continue;
            }

            if ($schedule->last_run_at && $schedule->last_run_at->setTimezone($timezone)->isSameDay($nowTz)) {
                continue;
            }

            $targetDate = $nowTz->copy()->startOfDay()->addDays($template->days_before);

            $rows = $this->dma->fetchExpiringOnDate($targetDate)
                ->filter(fn (array $row) => $row['status'] === 'active');

            foreach ($rows as $row) {
                $job = SmsJob::query()->firstOrCreate([
                    'customer_username' => $row['username'],
                    'template_id' => $template->id,
                    'expiration_date' => $row['expiration_date']->toDateString(),
                ], [
                    'phone' => $row['phone'],
                    'scheduled_for' => $nowTz->toDateString(),
                    'status' => 'pending',
                    'attempt_count' => 0,
                ]);

                if ($job->wasRecentlyCreated) {
                    $created++;
                    SendSmsJob::dispatch($job->id);
                } else {
                    $skipped++;
                }

                CustomerCache::query()->updateOrCreate([
                    'username' => $row['username'],
                ], [
                    'phone' => $row['phone'],
                    'expiration_date' => $row['expiration_date']->toDateString(),
                    'last_seen_at' => Carbon::now(),
                ]);
            }

            $schedule->last_run_at = $now;
            $schedule->save();
        }

        return [
            'created' => $created,
            'skipped' => $skipped,
        ];
    }
}
