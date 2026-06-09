<?php

namespace App\Payments;

use App\Enums\PaymentGateway;
use App\Payments\Contracts\PaymentGatewayInterface;
use App\Services\Settings\PaymentSettingsService;
use App\Payments\Gateways\MidtransGateway;
use App\Payments\Gateways\TripayGateway;
use App\Payments\Gateways\XenditGateway;
use InvalidArgumentException;

class PaymentGatewayManager
{
    public function driver(?string $gateway = null): PaymentGatewayInterface
    {
        $name = $gateway ?? app(PaymentSettingsService::class)->defaultGateway();

        return match ($name) {
            PaymentGateway::Midtrans->value => app(MidtransGateway::class),
            PaymentGateway::Xendit->value => app(XenditGateway::class),
            PaymentGateway::Tripay->value => app(TripayGateway::class),
            default => throw new InvalidArgumentException("Unsupported payment gateway: {$name}"),
        };
    }
}
