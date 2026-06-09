<?php

namespace App\Models;

use App\Traits\HasPublicUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PortfolioCategory extends Model
{
    use HasPublicUuid;

    protected $fillable = ['name', 'slug', 'sort_order', 'is_active'];

    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }
}
