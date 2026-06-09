<?php

namespace App\Models;

use App\Support\PublicMediaUrl;
use App\Enums\ServiceType;
use App\Models\Concerns\HasSeo;
use App\Models\Template;
use App\Traits\HasPublicUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasPublicUuid;
    use HasSeo;
    use SoftDeletes;

    protected $fillable = [
        'service_category_id',
        'template_id',
        'name',
        'slug',
        'type',
        'short_description',
        'description',
        'preview_image',
        'demo_url',
        'starting_price',
        'delivery_estimate',
        'features',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'type' => ServiceType::class,
            'starting_price' => 'decimal:2',
            'features' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        if ($this->preview_image) {
            return PublicMediaUrl::fromPath($this->preview_image);
        }

        if ($this->template?->preview_image) {
            return PublicMediaUrl::fromPath($this->template->preview_image);
        }

        return null;
    }

    public function getViewUrlAttribute(): string
    {
        if ($this->demo_url) {
            return $this->demo_url;
        }

        if ($this->template?->demo_url) {
            return $this->template->demo_url;
        }

        if ($this->template) {
            return route('templates.show', $this->template->slug);
        }

        return route('services.show', $this->slug);
    }

    public function isExternalViewUrl(): bool
    {
        $url = $this->view_url;

        return str_starts_with($url, 'http://') || str_starts_with($url, 'https://');
    }

    public function getBuyUrlAttribute(): string
    {
        if ($this->template) {
            return route('checkout', ['flow' => 'template_first', 'template' => $this->template->id]);
        }

        return match ($this->type) {
            ServiceType::UmkmWebsite,
            ServiceType::CompanyProfile,
            ServiceType::Ecommerce,
            ServiceType::School,
            ServiceType::Government => route('checkout', ['flow' => 'template_first']),
            default => route('contact'),
        };
    }

    public function getPopularityLabelAttribute(): string
    {
        if ($this->template && $this->template->downloads_count > 0) {
            return number_format($this->template->downloads_count, 0, ',', '.').' orang memilih ini';
        }

        return 'Layanan populer';
    }
}
