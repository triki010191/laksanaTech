<?php

namespace App\DTO\Payments;

readonly class WebhookPayloadDTO
{
    public function __construct(
        public string $gateway,
        public string $eventType,
        public array $payload,
        public ?string $signature = null,
    ) {}
}
