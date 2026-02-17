<?php

namespace App\Services;

use App\Models\SmsGateway;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class DinstarGatewayClient implements SmsGatewayClient
{
    public function send(SmsGateway $gateway, string $to, string $message): SmsGatewayResponse
    {
        $url = $this->buildUrl($gateway);

        $payload = [
            'to' => $to,
            'message' => $message,
            'senderId' => $gateway->sender_id,
        ];

        try {
            $client = $this->buildClient($gateway);

            if ($gateway->request_type === 'query') {
                $response = $client->withQueryParameters($payload)->post($url);
            } else {
                $response = $client->post($url, $payload);
            }

            $raw = $this->parseResponse($response->body());

            return new SmsGatewayResponse(
                $response->successful(),
                $raw['message_id'] ?? $raw['id'] ?? null,
                $raw,
                $response->status(),
                $response->successful() ? null : ($raw['error'] ?? $response->body())
            );
        } catch (\Throwable $e) {
            return new SmsGatewayResponse(false, null, ['exception' => $e->getMessage()], null, $e->getMessage());
        }
    }

    protected function buildClient(SmsGateway $gateway): PendingRequest
    {
        $client = Http::timeout(15);

        if ($gateway->auth_type === 'basic' && $gateway->username && $gateway->password) {
            $client = $client->withBasicAuth($gateway->username, $gateway->password);
        }

        if ($gateway->auth_type === 'bearer' && $gateway->token) {
            $client = $client->withToken($gateway->token);
        }

        return $client;
    }

    protected function buildUrl(SmsGateway $gateway): string
    {
        $base = rtrim((string) $gateway->base_url, '/');
        $path = trim((string) $gateway->endpoint_path, '/');

        return $path ? $base.'/'.$path : $base;
    }

    protected function parseResponse(string $body): array
    {
        $decoded = json_decode($body, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded ?? [];
        }

        return ['raw' => $body];
    }
}
