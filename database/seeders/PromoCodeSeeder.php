<?php

namespace Database\Seeders;

use App\Models\PromoCode;
use Illuminate\Database\Seeder;

class PromoCodeSeeder extends Seeder
{
    public function run(): void
    {
        PromoCode::query()->updateOrCreate(
            ['code' => 'WEBSITEJUARA'],
            [
                'label' => 'Diskon pembuatan website',
                'type' => 'fixed',
                'value' => 500000,
                'min_order_total' => 500000,
                'is_active' => true,
            ],
        );
    }
}
