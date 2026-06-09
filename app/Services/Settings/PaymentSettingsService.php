<?php

namespace App\Services\Settings;

use App\Enums\PaymentGateway;

class PaymentSettingsService
{
    public function __construct(
        private readonly SiteSettingsService $site,
    ) {}

    public function defaultGateway(): string
    {
        $gateway = $this->site->get('payment_default_gateway');

        if ($gateway && in_array($gateway, $this->availableGateways(), true)) {
            return $gateway;
        }

        return config('laksana.payment.default_gateway', PaymentGateway::Xendit->value);
    }

    /** @return list<string> */
    public function availableGateways(): array
    {
        return array_map(fn (PaymentGateway $g) => $g->value, PaymentGateway::cases());
    }

    /** @return array<string, string> */
    public function labels(): array
    {
        return [
            PaymentGateway::Midtrans->value => 'Midtrans',
            PaymentGateway::Xendit->value => 'Xendit',
            PaymentGateway::Tripay->value => 'Tripay',
        ];
    }

    public function isEnabled(): bool
    {
        return ($this->site->get('payment_enabled', '1') ?? '1') === '1';
    }

    public function isConfigured(?string $gateway = null): bool
    {
        $gateway = $gateway ?? $this->defaultGateway();

        return match ($gateway) {
            PaymentGateway::Midtrans->value => filled($this->midtransServerKey()),
            PaymentGateway::Xendit->value => filled($this->xenditSecretKey()),
            PaymentGateway::Tripay->value => filled($this->tripayApiKey()) && filled($this->tripayMerchantCode()),
            default => false,
        };
    }

    public function midtransServerKey(): ?string
    {
        return $this->site->get('payment_midtrans_server_key') ?: config('midtrans.server_key');
    }

    public function midtransClientKey(): ?string
    {
        return $this->site->get('payment_midtrans_client_key') ?: config('midtrans.client_key');
    }

    public function midtransIsProduction(): bool
    {
        $val = $this->site->get('payment_midtrans_is_production');

        if ($val !== null && $val !== '') {
            return $val === '1';
        }

        return (bool) config('midtrans.is_production', false);
    }

    public function midtransSnapUrl(): string
    {
        return $this->midtransIsProduction()
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    public function xenditSecretKey(): ?string
    {
        return $this->site->get('payment_xendit_secret_key') ?: env('XENDIT_SECRET_KEY');
    }

    public function xenditPublicKey(): ?string
    {
        return $this->site->get('payment_xendit_public_key') ?: env('XENDIT_PUBLIC_KEY');
    }

    public function tripayMerchantCode(): ?string
    {
        return $this->site->get('payment_tripay_merchant_code') ?: env('TRIPAY_MERCHANT_CODE');
    }

    public function tripayApiKey(): ?string
    {
        return $this->site->get('payment_tripay_api_key') ?: env('TRIPAY_API_KEY');
    }

    public function tripayPrivateKey(): ?string
    {
        return $this->site->get('payment_tripay_private_key') ?: env('TRIPAY_PRIVATE_KEY');
    }

    public function tripayIsProduction(): bool
    {
        $val = $this->site->get('payment_tripay_is_production');

        if ($val !== null && $val !== '') {
            return $val === '1';
        }

        return (bool) env('TRIPAY_IS_PRODUCTION', false);
    }

    /** @return array<string, mixed> */
    public function allForAdmin(): array
    {
        return [
            'payment_enabled' => $this->isEnabled(),
            'payment_default_gateway' => $this->defaultGateway(),
            'payment_midtrans_is_production' => $this->midtransIsProduction(),
            'payment_tripay_is_production' => $this->tripayIsProduction(),
            'has_midtrans_server_key' => filled($this->midtransServerKey()),
            'has_midtrans_client_key' => filled($this->midtransClientKey()),
            'has_xendit_secret_key' => filled($this->xenditSecretKey()),
            'has_xendit_public_key' => filled($this->xenditPublicKey()),
            'has_tripay_merchant_code' => filled($this->tripayMerchantCode()),
            'has_tripay_api_key' => filled($this->tripayApiKey()),
            'has_tripay_private_key' => filled($this->tripayPrivateKey()),
        ];
    }
}
