<?php

namespace App\Models;

use App\Traits\HasPublicUuid;
use Illuminate\Database\Eloquent\Model;

class DomainExtension extends Model
{
    use HasPublicUuid;

    protected $fillable = [
        'extension',
        'label',
        'price',
        'compare_price',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}
