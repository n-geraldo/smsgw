<?php

namespace App\Services;

use App\Models\SmsGateway;

interface SmsGatewayClient
{
    public function send(SmsGateway $gateway, string $to, string $message): SmsGatewayResponse;
}
