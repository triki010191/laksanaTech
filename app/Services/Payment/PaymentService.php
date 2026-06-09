<?php

namespace App\Services\Payment;

use App\DTO\Payments\CreatePaymentDTO;
use App\DTO\Payments\PaymentResultDTO;
use App\DTO\Payments\WebhookPayloadDTO;
use App\Events\PaymentReceived;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentWebhook;
use App\Payments\PaymentGatewayManager;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(
        private readonly PaymentGatewayManager $gateways,
    ) {}

    public function initiate(Invoice $invoice, ?string $gateway = null): PaymentResultDTO
    {
        $driver = $this->gateways->driver($gateway);

        $dto = new CreatePaymentDTO(
            invoiceId: $invoice->id,
            userId: $invoice->user_id,
            amount: (float) $invoice->amount,
            description: 'Invoice '.$invoice->invoice_number,
            customer: [
                'name' => $invoice->user->name,
                'email' => $invoice->user->email,
            ],
        );

        $result = $driver->createCharge($dto);

        if ($result->success) {
            Payment::query()->updateOrCreate(
                [
                    'invoice_id' => $invoice->id,
                    'gateway' => $driver->getName(),
                    'status' => 'pending',
                ],
                [
                    'user_id' => $invoice->user_id,
                    'external_id' => $result->externalId,
                    'amount' => $invoice->amount,
                    'gateway_response' => $result->raw,
                ],
            );
        }

        return $result;
    }

    public function processWebhook(WebhookPayloadDTO $payload): Payment
    {
        PaymentWebhook::query()->create([
            'gateway' => $payload->gateway,
            'event_type' => $payload->eventType,
            'external_id' => $payload->payload['external_id'] ?? null,
            'payload' => $payload->payload,
            'status' => 'received',
        ]);

        return DB::transaction(function () use ($payload) {
            $payment = $this->gateways->driver($payload->gateway)->handleWebhook($payload);

            PaymentWebhook::query()
                ->where('gateway', $payload->gateway)
                ->where('status', 'received')
                ->latest()
                ->limit(1)
                ->update(['status' => 'processed']);

            if ($payment->status === 'paid') {
                event(new PaymentReceived($payment));
            }

            return $payment;
        });
    }
}
