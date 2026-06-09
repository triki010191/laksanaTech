<?php

namespace App\Services\Home;

use App\Models\DomainExtension;
use App\Models\Template;
use App\Models\WebsitePackage;
use Illuminate\Support\Collection;

class HomePageService
{
    public function featuredTemplates(int $limit = 3): Collection
    {
        return Template::query()
            ->with('category')
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->orderByDesc('downloads_count')
            ->limit($limit)
            ->get();
    }

    /**
     * @return Collection<int, array{offer: array, extension: \App\Models\DomainExtension, package: \App\Models\WebsitePackage, price: float, compare_price: float}>
     */
    public function domainOffers(): Collection
    {
        $package = WebsitePackage::query()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->first()
            ?? WebsitePackage::query()->where('is_active', true)->orderBy('sort_order')->first();

        if (! $package) {
            return collect();
        }

        $promo = config('laksana.home.section_03.promo_code', '');

        return collect(config('laksana.domain_offers', []))->map(function (array $offer) use ($package, $promo) {
            $extension = DomainExtension::query()
                ->where('is_active', true)
                ->where('extension', $offer['extension_key'])
                ->first();

            if (! $extension) {
                return null;
            }

            $price = (float) $extension->price + (float) $package->price;

            return [
                'offer' => $offer,
                'extension' => $extension,
                'package' => $package,
                'price' => $price,
                'compare_price' => round($price * 1.25, -3),
                'promo_code' => $promo,
            ];
        })->filter()->values();
    }
}
