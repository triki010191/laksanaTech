<?php

namespace App\Payments\Gateways;

use App\DTO\Payments\CreatePaymentDTO;
use App\DTO\Payments\PaymentResultDTO;
use App\DTO\Payments\WebhookPayloadDTO;
use App\Models\Payment;
use App\Payments\Contracts\PaymentGatewayInterface;

abstract class AbstractPaymentGateway implements PaymentGatewayInterface
{
    abstract public function getName(): string;

    public function createCharge(CreatePaymentDTO $dto): PaymentResultDTO
    {
        return new PaymentResultDTO(
            success: false,
            status: 'pending',
            message: sprintf('%s integration pending configuration.', $this->getName()),
        );
    }

    public function handleWebhook(WebhookPayloadDTO $payload): Payment
    {
        throw new \RuntimeException('Webhook handler not implemented for '.$this->getName());
    }

    public function refund(Payment $payment, ?float $amount = null): PaymentResultDTO
    {
        return new PaymentResultDTO(
            success: false,
            status: 'refunded',
            message: 'Refund flow ready — gateway implementation required.',
        );
    }
}
