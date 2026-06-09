<?php

namespace App\Services\Commerce;

use App\Models\DomainExtension;
use App\Models\Template;

class CheckoutPricingCalculator
{
    /**
     * @return array{
     *   domain_price: float,
     *   template_price: float,
     *   hosting_price: float,
     *   admin_fee: float,
     *   addon_price: float,
     *   subtotal: float,
     *   discount: float,
     *   total: float,
     *   years: int,
     *   plan: array<string, mixed>|null
     * }
     */
    public function calculate(
        ?DomainExtension $extension,
        ?Template $template,
        int $years,
        bool $withEditingAddon = false,
        float $promoDiscount = 0,
    ): array {
        $plan = collect(config('laksana.checkout.subscription_plans', []))
            ->firstWhere('years', $years);

        $domainUnit = $extension ? (float) $extension->price : 0;
        $domainPrice = $domainUnit * max(1, $years);
        $templatePrice = $template ? (float) ($template->sale_price ?? $template->price) : 0;
        $hostingPrice = $plan ? (float) ($plan['hosting_price'] ?? 0) : 0;
        $adminFee = (float) config('laksana.checkout.admin_fee', 0);
        $addonPrice = $withEditingAddon ? (float) config('laksana.checkout.editing_addon_price', 0) : 0;

        $subtotal = $domainPrice + $templatePrice + $hostingPrice + $adminFee + $addonPrice;
        $discount = min($promoDiscount, $subtotal);
        $total = max(0, $subtotal - $discount);

        return [
            'domain_price' => $domainPrice,
            'template_price' => $templatePrice,
            'hosting_price' => $hostingPrice,
            'admin_fee' => $adminFee,
            'addon_price' => $addonPrice,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'years' => $years,
            'plan' => $plan,
        ];
    }
}
