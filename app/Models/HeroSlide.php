<?php

namespace App\Models;

use App\Support\PublicMediaUrl;
use App\Traits\HasPublicUuid;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    use HasPublicUuid;

    protected $fillable = [
        'badge',
        'title',
        'emphasis',
        'description',
        'image_path',
        'cta_primary_label',
        'cta_primary_route',
        'cta_secondary_label',
        'cta_secondary_route',
        'cta_secondary_url',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getImageUrlAttribute(): string
    {
        return PublicMediaUrl::fromPath($this->image_path) ?? '';
    }

    /** @return array<string, mixed> */
    public function toSlideArray(): array
    {
        $slide = [
            'badge' => $this->badge,
            'title' => $this->title,
            'emphasis' => $this->emphasis,
            'description' => $this->description,
            'image_url' => $this->image_url,
        ];

        if ($this->cta_primary_label) {
            $slide['cta_primary'] = [
                'label' => $this->cta_primary_label,
                'route' => $this->cta_primary_route,
            ];
        }

        if ($this->cta_secondary_label) {
            $slide['cta_secondary'] = [
                'label' => $this->cta_secondary_label,
                'route' => $this->cta_secondary_route,
                'url' => $this->cta_secondary_url,
            ];
        }

        return $slide;
    }
}
