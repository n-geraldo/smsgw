<?php

namespace App\Services;

use App\Models\SmsGateway;
use App\Models\SmsJob;
use App\Models\SmsLog;
use Carbon\Carbon;

class SmsDispatcher
{
    public function __construct(
        protected TemplateRenderer $renderer,
        protected SmsGatewayClient $gatewayClient
    ) {}

    public function send(SmsJob $job, ?SmsGateway $gatewayOverride = null): SmsGatewayResponse
    {
        $gateway = $gatewayOverride ?: SmsGateway::query()
            ->where('is_active', true)
            ->orderByDesc('is_default')
            ->first();

        if (! $gateway) {
            return new SmsGatewayResponse(false, null, [], null, 'No active SMS gateway configured.');
        }

        $template = $job->template;

        if (! $template) {
            return new SmsGatewayResponse(false, null, [], null, 'Template not found.');
        }

        $message = $this->renderer->render($template->message_body, [
            'username' => $job->customer_username,
            'phone' => $job->phone,
            'expiration_date' => $job->expiration_date,
        ]);

        $response = $this->gatewayClient->send($gateway, $job->phone, $message);

        SmsLog::query()->create([
            'sms_job_id' => $job->id,
            'request_payload' => [
                'gateway_id' => $gateway->id,
                'to' => $job->phone,
                'message' => $message,
                'sender_id' => $gateway->sender_id,
            ],
            'response_payload' => $response->raw,
            'http_status' => $response->httpStatus,
            'created_at' => Carbon::now(),
        ]);

        return $response;
    }
}
