<?php

namespace App\Services\Content;

use App\Models\HeroSlide;
use App\Support\PublicMediaUrl;
use Illuminate\Support\Collection;

class HeroSlideService
{
    /** @return Collection<int, array<string, mixed>> */
    public function activeSlides(): Collection
    {
        $slides = HeroSlide::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (HeroSlide $slide) => $slide->toSlideArray());

        if ($slides->isNotEmpty()) {
            return $slides;
        }

        return collect(config('laksana.hero_slides', []))
            ->map(function (array $slide): array {
                $path = $slide['image'] ?? '';
                $slide['image_url'] = PublicMediaUrl::fromPath($path) ?? '';

                return $slide;
            });
    }
}
