<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Traits\HasPublicUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasPublicUuid;
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'type',
        'checkout_flow',
        'status',
        'subtotal',
        'discount',
        'tax',
        'total',
        'currency',
        'notes',
        'metadata',
        'estimated_ready_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'metadata' => 'array',
            'estimated_ready_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
