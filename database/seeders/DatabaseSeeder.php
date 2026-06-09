<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            LaksanaContentSeeder::class,
            PortfolioSeeder::class,
            HeroSlideSeeder::class,
            MarketplaceSeeder::class,
            BlogSeeder::class,
            FaqSeeder::class,
            DemoContentImagesSeeder::class,
            PromoCodeSeeder::class,
            SiteSettingsSeeder::class,
            DemoCustomerSeeder::class,
            DemoStaffSeeder::class,
        ]);
    }
}
