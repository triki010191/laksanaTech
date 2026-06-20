<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use App\Support\LiveScreenshotUrl;
use App\Support\PublicMediaUrl;
use App\Traits\HasPublicUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use HasPublicUuid;
    use HasSeo;
    use SoftDeletes;

    protected $fillable = [
        'template_category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'price',
        'sale_price',
        'demo_url',
        'live_demo_url',
        'preview_image',
        'gallery',
        'tech_stack',
        'downloads_count',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'gallery' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function getPreviewImageUrlAttribute(): ?string
    {
        return PublicMediaUrl::fromPath($this->preview_image);
    }

    /** URL demo eksternal (ThemeForest, Envato, dll.) — prioritas live_demo_url. */
    public function getLiveDemoLinkAttribute(): ?string
    {
        return $this->live_demo_url ?: $this->demo_url;
    }

    /** Daftar sumber gambar thumbnail: proxy lokal → upload manual. */
    public function getThumbnailSourcesAttribute(): array
    {
        $sources = [];

        if ($link = $this->live_demo_link) {
            $sources[] = LiveScreenshotUrl::proxy($link);
        }

        if ($preview = $this->preview_image_url) {
            $sources[] = $preview;
        }

        return $sources;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TemplateCategory::class, 'template_category_id');
    }
}
