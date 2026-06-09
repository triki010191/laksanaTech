<?php

namespace App\Services\Commerce;

use App\Models\PromoCode;

class PromoCodeService
{
    /**
     * @return array{valid: bool, code: string|null, discount: float, message: string}
     */
    public function apply(string $code, float $subtotalBeforeDiscount): array
    {
        $code = strtoupper(trim($code));

        if ($code === '') {
            return ['valid' => false, 'code' => null, 'discount' => 0, 'message' => 'Masukkan kode promo.'];
        }

        $promo = PromoCode::query()
            ->where('code', $code)
            ->where('is_active', true)
            ->first();

        if (! $promo) {
            return ['valid' => false, 'code' => null, 'discount' => 0, 'message' => 'Kode promo tidak valid.'];
        }

        if ($promo->starts_at && now()->lt($promo->starts_at)) {
            return ['valid' => false, 'code' => null, 'discount' => 0, 'message' => 'Kode promo belum aktif.'];
        }

        if ($promo->ends_at && now()->gt($promo->ends_at)) {
            return ['valid' => false, 'code' => null, 'discount' => 0, 'message' => 'Kode promo sudah kedaluwarsa.'];
        }

        if ($promo->max_uses && $promo->used_count >= $promo->max_uses) {
            return ['valid' => false, 'code' => null, 'discount' => 0, 'message' => 'Kuota kode promo habis.'];
        }

        if ($promo->min_order_total && $subtotalBeforeDiscount < (float) $promo->min_order_total) {
            return [
                'valid' => false,
                'code' => null,
                'discount' => 0,
                'message' => 'Minimum order Rp '.number_format($promo->min_order_total, 0, ',', '.'),
            ];
        }

        $discount = $promo->type === 'percent'
            ? round($subtotalBeforeDiscount * ((float) $promo->value / 100), 0)
            : (float) $promo->value;

        return [
            'valid' => true,
            'code' => $promo->code,
            'discount' => min($discount, $subtotalBeforeDiscount),
            'message' => 'Kode promo berhasil diterapkan.',
        ];
    }
}
