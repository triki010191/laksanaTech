<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        if (HeroSlide::query()->exists()) {
            return;
        }

        $defaults = config('laksana.hero_slides', []);

        foreach ($defaults as $index => $slide) {
            HeroSlide::query()->create([
                'badge' => $slide['badge'] ?? null,
                'title' => $slide['title'] ?? 'Slide '.($index + 1),
                'emphasis' => $slide['emphasis'] ?? null,
                'description' => $slide['description'] ?? null,
                'image_path' => $slide['image'] ?? 'images/hero/slide-'.($index + 1).'.png',
                'cta_primary_label' => $slide['cta_primary']['label'] ?? null,
                'cta_primary_route' => $slide['cta_primary']['route'] ?? null,
                'cta_secondary_label' => $slide['cta_secondary']['label'] ?? null,
                'cta_secondary_route' => $slide['cta_secondary']['route'] ?? null,
                'cta_secondary_url' => $slide['cta_secondary']['url'] ?? null,
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }
}
