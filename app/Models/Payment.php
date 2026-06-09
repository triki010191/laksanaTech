<?php

namespace App\Models;

use App\Enums\PaymentGateway;
use App\Traits\HasPublicUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasPublicUuid;

    protected $fillable = [
        'invoice_id',
        'user_id',
        'gateway',
        'external_id',
        'payment_method',
        'status',
        'amount',
        'currency',
        'gateway_response',
        'paid_at',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'gateway' => PaymentGateway::class,
            'amount' => 'decimal:2',
            'gateway_response' => 'array',
            'paid_at' => 'datetime',
            'expired_at' => 'datetime',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
