<?php

namespace App\Enums;

enum PaymentGateway: string
{
    case Midtrans = 'midtrans';
    case Xendit = 'xendit';
    case Tripay = 'tripay';
}
