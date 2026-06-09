<?php

namespace App\Payments\Gateways;

class TripayGateway extends AbstractPaymentGateway
{
    public function getName(): string
    {
        return 'tripay';
    }
}
