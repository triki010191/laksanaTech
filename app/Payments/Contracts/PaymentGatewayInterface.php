<?php

namespace App\Payments\Contracts;

use App\DTO\Payments\CreatePaymentDTO;
use App\DTO\Payments\PaymentResultDTO;
use App\DTO\Payments\WebhookPayloadDTO;
use App\Models\Payment;

interface PaymentGatewayInterface
{
    public function getName(): string;

    public function createCharge(CreatePaymentDTO $dto): PaymentResultDTO;

    public function handleWebhook(WebhookPayloadDTO $payload): Payment;

    public function refund(Payment $payment, ?float $amount = null): PaymentResultDTO;
}
