<?php

namespace App\Payments\Gateways;

use App\DTO\Payments\CreatePaymentDTO;
use App\DTO\Payments\PaymentResultDTO;
use App\Models\Invoice;
use App\Services\Settings\PaymentSettingsService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XenditGateway extends AbstractPaymentGateway
{
    public function __construct(
        private readonly PaymentSettingsService $settings,
    ) {}

    public function getName(): string
    {
        return 'xendit';
    }

    public function createCharge(CreatePaymentDTO $dto): PaymentResultDTO
    {
        $secretKey = $this->settings->xenditSecretKey();
        if (empty($secretKey)) {
            return new PaymentResultDTO(
                success: false,
                status: 'failed',
                message: 'Xendit secret key belum dikonfigurasi. Isi di Admin → Payment Gateway.',
            );
        }

        $invoice = Invoice::query()->findOrFail($dto->invoiceId);
        $externalId = 'LKT-INV-'.$invoice->id.'-'.time();

        $payload = [
            'external_id' => $externalId,
            'amount' => (int) round($dto->amount),
            'description' => $dto->description ?? 'Invoice Payment',
            'customer' => [
                'given_names' => $dto->customer['name'] ?? 'Customer',
                'email' => $dto->customer['email'] ?? 'customer@example.com',
            ],
            'success_redirect_url' => route('payment.finish'),
            'failure_redirect_url' => route('checkout.payment', $invoice),
        ];

        $response = Http::withBasicAuth($secretKey, '')
            ->acceptJson()
            ->post('https://api.xendit.co/v2/invoices', $payload);

        if (! $response->successful()) {
            Log::error('Xendit invoice error', ['body' => $response->json(), 'status' => $response->status()]);

            return new PaymentResultDTO(
                success: false,
                status: 'failed',
                message: $response->json('message') ?? 'Gagal membuat invoice Xendit',
                raw: $response->json() ?? [],
            );
        }

        $data = $response->json();

        return new PaymentResultDTO(
            success: true,
            status: 'pending',
            externalId: $externalId,
            redirectUrl: $data['invoice_url'] ?? null,
            raw: $data,
        );
    }
}
