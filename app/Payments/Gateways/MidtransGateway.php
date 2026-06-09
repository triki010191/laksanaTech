<?php

namespace App\Payments\Gateways;

use App\DTO\Payments\CreatePaymentDTO;
use App\DTO\Payments\PaymentResultDTO;
use App\DTO\Payments\WebhookPayloadDTO;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\Settings\PaymentSettingsService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class MidtransGateway extends AbstractPaymentGateway
{
    public function __construct(
        private readonly PaymentSettingsService $settings,
    ) {}

    public function getName(): string
    {
        return 'midtrans';
    }

    public function createCharge(CreatePaymentDTO $dto): PaymentResultDTO
    {
        $serverKey = $this->settings->midtransServerKey();
        if (empty($serverKey)) {
            return new PaymentResultDTO(
                success: false,
                status: 'failed',
                message: 'Midtrans server key belum dikonfigurasi. Isi di Admin → Payment Gateway.',
            );
        }

        $invoice = Invoice::query()->findOrFail($dto->invoiceId);
        $orderId = 'LKT-INV-'.$invoice->id.'-'.time();

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) round($dto->amount),
            ],
            'customer_details' => [
                'first_name' => $dto->customer['name'] ?? 'Customer',
                'email' => $dto->customer['email'] ?? 'customer@example.com',
                'phone' => $dto->customer['phone'] ?? '',
            ],
            'item_details' => [
                [
                    'id' => (string) $invoice->id,
                    'price' => (int) round($dto->amount),
                    'quantity' => 1,
                    'name' => $dto->description ?? 'Invoice Payment',
                ],
            ],
            'callbacks' => [
                'finish' => route('payment.finish'),
            ],
        ];

        $response = Http::withBasicAuth($serverKey, '')
            ->acceptJson()
            ->post($this->settings->midtransSnapUrl(), $payload);

        if (! $response->successful()) {
            Log::error('Midtrans Snap error', ['body' => $response->json(), 'status' => $response->status()]);

            return new PaymentResultDTO(
                success: false,
                status: 'failed',
                message: $response->json('error_messages.0') ?? 'Gagal membuat transaksi Midtrans',
                raw: $response->json() ?? [],
            );
        }

        $data = $response->json();

        return new PaymentResultDTO(
            success: true,
            status: 'pending',
            externalId: $orderId,
            redirectUrl: $data['redirect_url'] ?? null,
            raw: $data,
        );
    }

    public function handleWebhook(WebhookPayloadDTO $payload): Payment
    {
        $data = $payload->payload;
        $orderId = $data['order_id'] ?? null;
        $status = $this->mapTransactionStatus($data['transaction_status'] ?? '');

        if (! $orderId) {
            throw new RuntimeException('Midtrans webhook missing order_id');
        }

        $payment = Payment::query()->where('external_id', $orderId)->firstOrFail();
        $payment->update([
            'status' => $status,
            'gateway_response' => array_merge($payment->gateway_response ?? [], $data),
            'payment_method' => $data['payment_type'] ?? $payment->payment_method,
            'paid_at' => in_array($status, ['paid'], true) ? now() : $payment->paid_at,
        ]);

        if ($status === 'paid') {
            $payment->invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            $order = $payment->invoice->order;
            if ($order) {
                $meta = $order->metadata ?? [];
                $meta['fulfillment_status'] = 'waiting_setup';

                $order->update([
                    'status' => 'in_progress',
                    'estimated_ready_at' => now()->addHours(48),
                    'metadata' => $meta,
                ]);
            }
        }

        return $payment->fresh();
    }

    private function mapTransactionStatus(string $midtransStatus): string
    {
        return match ($midtransStatus) {
            'capture', 'settlement' => 'paid',
            'pending' => 'pending',
            'deny', 'cancel' => 'failed',
            'expire' => 'expired',
            'refund', 'partial_refund' => 'refunded',
            default => 'processing',
        };
    }
}
