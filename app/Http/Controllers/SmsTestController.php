<?php

namespace App\Http\Controllers;

use App\Http\Requests\SmsTestRequest;
use App\Models\SmsGateway;
use App\Models\SmsJob;
use App\Models\SmsTemplate;
use App\Services\SmsDispatcher;

class SmsTestController extends Controller
{
    public function show()
    {
        $templates = SmsTemplate::query()->orderBy('days_before')->get();
        $gateways = SmsGateway::query()->orderByDesc('is_default')->get();

        return view('sms.test', compact('templates', 'gateways'));
    }

    public function send(SmsTestRequest $request, SmsDispatcher $dispatcher)
    {
        $template = SmsTemplate::query()->findOrFail($request->integer('template_id'));
        $gateway = $request->filled('gateway_id')
            ? SmsGateway::query()->find($request->integer('gateway_id'))
            : null;

        $job = SmsJob::query()->create([
            'customer_username' => 'test-'.now()->timestamp,
            'phone' => (string) $request->string('phone'),
            'template_id' => $template->id,
            'scheduled_for' => now()->toDateString(),
            'expiration_date' => now()->toDateString(),
            'status' => 'pending',
            'attempt_count' => 0,
        ]);

        $response = $dispatcher->send($job, $gateway);

        if ($response->success) {
            $job->status = 'sent';
            $job->provider_message_id = $response->providerMessageId;
            $job->sent_at = now();
            $job->last_error = null;
            $job->save();

            return back()->with('status', 'Test SMS sent.');
        }

        $job->status = 'failed';
        $job->last_error = $response->errorMessage;
        $job->save();

        return back()->with('status', 'Test SMS failed: '.$response->errorMessage);
    }
}
