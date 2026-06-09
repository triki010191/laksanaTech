<?php

namespace App\DTO\Payments;

readonly class CreatePaymentDTO
{
    public function __construct(
        public int $invoiceId,
        public int $userId,
        public float $amount,
        public string $currency = 'IDR',
        public ?string $description = null,
        public array $customer = [],
        public array $metadata = [],
    ) {}
}
