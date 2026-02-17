<?php

namespace App\Jobs;

use App\Models\SmsJob;
use App\Services\SmsDispatcher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use RuntimeException;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public int $smsJobId) {}

    public function backoff(): array
    {
        return [60, 300, 900];
    }

    public function middleware(): array
    {
        return [new RateLimited('sms-send')];
    }

    public function handle(SmsDispatcher $dispatcher): void
    {
        $job = SmsJob::query()->with('template')->find($this->smsJobId);

        if (! $job || $job->status === 'sent') {
            return;
        }

        $job->attempt_count = $this->attempts();
        $job->save();

        $response = $dispatcher->send($job);

        if ($response->success) {
            $job->status = 'sent';
            $job->provider_message_id = $response->providerMessageId;
            $job->sent_at = now();
            $job->last_error = null;
            $job->save();

            return;
        }

        $job->last_error = $response->errorMessage;
        $job->status = $this->attempts() >= $this->tries ? 'failed' : 'pending';
        $job->save();

        throw new RuntimeException($response->errorMessage ?: 'SMS send failed.');
    }
}
