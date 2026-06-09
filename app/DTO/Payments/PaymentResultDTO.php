<?php

namespace App\DTO\Payments;

readonly class PaymentResultDTO
{
    public function __construct(
        public bool $success,
        public string $status,
        public ?string $externalId = null,
        public ?string $redirectUrl = null,
        public ?string $message = null,
        public array $raw = [],
    ) {}
}
