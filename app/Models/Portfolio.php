<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use App\Support\LiveScreenshotUrl;
use App\Traits\HasPublicUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Portfolio extends Model
{
    use HasPublicUuid;
    use HasSeo;
    use SoftDeletes;

    protected $fillable = [
        'portfolio_category_id',
        'title',
        'slug',
        'client_name',
        'client_logo',
        'project_url',
        'short_description',
        'case_study',
        'technologies',
        'completed_at',
        'is_featured',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'technologies' => 'array',
            'completed_at' => 'date',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PortfolioCategory::class, 'portfolio_category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(PortfolioImage::class)->orderBy('sort_order');
    }

    public function isWebsiteProject(): bool
    {
        return $this->category?->slug === 'website';
    }

    /** URL live untuk kategori Website (screenshot otomatis). */
    public function getLiveDemoLinkAttribute(): ?string
    {
        return $this->isWebsiteProject() && $this->project_url
            ? $this->project_url
            : null;
    }

    public function getManualCoverImageUrlAttribute(): ?string
    {
        $image = $this->relationLoaded('images')
            ? $this->images->first()
            : $this->images()->orderBy('sort_order')->first();

        return $image?->url ?: null;
    }

    /** Sumber thumbnail: screenshot proxy → foto manual. */
    public function getThumbnailSourcesAttribute(): array
    {
        $sources = [];

        if ($link = $this->live_demo_link) {
            $sources[] = LiveScreenshotUrl::proxy($link);
        }

        if ($manual = $this->manual_cover_image_url) {
            $sources[] = $manual;
        }

        return $sources;
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        $sources = $this->thumbnail_sources;

        return $sources[0] ?? null;
    }
}
