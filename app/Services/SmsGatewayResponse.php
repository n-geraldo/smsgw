<?php

namespace App\Services;

class SmsGatewayResponse
{
    public function __construct(
        public bool $success,
        public ?string $providerMessageId,
        public array $raw,
        public ?int $httpStatus,
        public ?string $errorMessage = null
    ) {}
}
