<?php

namespace Database\Seeders;

use App\Models\DomainExtension;
use App\Models\Template;
use App\Models\TemplateCategory;
use App\Models\WebsitePackage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MarketplaceSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['slug' => 'produk-ekspor', 'name' => 'Produk Ekspor', 'sort_order' => 1],
            ['slug' => 'agrikultur', 'name' => 'Agrikultur', 'sort_order' => 2],
            ['slug' => 'craft', 'name' => 'Craft', 'sort_order' => 3],
            ['slug' => 'company', 'name' => 'Company', 'sort_order' => 4],
            ['slug' => 'otomotif', 'name' => 'Otomotif', 'sort_order' => 5],
            ['slug' => 'restaurant', 'name' => 'Restaurant', 'sort_order' => 6],
            ['slug' => 'service-jasa', 'name' => 'Service / Jasa', 'sort_order' => 7],
            ['slug' => 'umkm', 'name' => 'Website UMKM', 'sort_order' => 8],
            ['slug' => 'toko-online', 'name' => 'Toko Online', 'sort_order' => 9],
            ['slug' => 'sekolah', 'name' => 'Web Sekolah', 'sort_order' => 10],
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $categoryIds[$cat['slug']] = TemplateCategory::query()->updateOrCreate(
                ['slug' => $cat['slug']],
                array_merge($cat, ['is_active' => true]),
            )->id;
        }

        TemplateCategory::query()
            ->where('slug', 'website')
            ->update(['is_active' => false]);

        $templates = $this->verifiedTemplates();

        foreach ($templates as $data) {
            Template::query()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'template_category_id' => $categoryIds[$data['category']],
                    'name' => $data['name'],
                    'short_description' => $data['short_description'],
                    'description' => $data['short_description'],
                    'price' => $data['price'],
                    'sale_price' => $data['sale_price'],
                    'live_demo_url' => $data['live_demo_url'],
                    'demo_url' => null,
                    'preview_image' => null,
                    'tech_stack' => 'HTML, Bootstrap',
                    'is_featured' => $data['is_featured'],
                    'is_active' => true,
                    'downloads_count' => $data['downloads_count'],
                ],
            );
        }

        $activeSlugs = array_column($templates, 'slug');
        Template::query()
            ->whereNotIn('slug', $activeSlugs)
            ->update(['is_active' => false]);

        $extensions = [
            ['extension' => '.com', 'label' => 'Domain .COM', 'price' => 97000, 'compare_price' => 147000, 'sort_order' => 1],
            ['extension' => '.co.id', 'label' => 'Domain .CO.ID', 'price' => 300000, 'compare_price' => 450000, 'sort_order' => 2],
            ['extension' => '.id', 'label' => 'Domain .ID', 'price' => 250000, 'compare_price' => 350000, 'sort_order' => 3],
            ['extension' => '.net', 'label' => 'Domain .NET', 'price' => 120000, 'compare_price' => 175000, 'sort_order' => 4],
            ['extension' => '.org', 'label' => 'Domain .ORG', 'price' => 110000, 'compare_price' => 165000, 'sort_order' => 5],
            ['extension' => '.web.id', 'label' => 'Domain .WEB.ID', 'price' => 45000, 'compare_price' => 75000, 'sort_order' => 6],
            ['extension' => '.my.id', 'label' => 'Domain .MY.ID', 'price' => 35000, 'compare_price' => 55000, 'sort_order' => 7],
            ['extension' => '.biz.id', 'label' => 'Domain .BIZ.ID', 'price' => 85000, 'compare_price' => 120000, 'sort_order' => 8],
            ['extension' => '.sch.id', 'label' => 'Domain .SCH.ID', 'price' => 200000, 'compare_price' => 280000, 'sort_order' => 9],
            ['extension' => '.ac.id', 'label' => 'Domain .AC.ID', 'price' => 220000, 'compare_price' => 310000, 'sort_order' => 10],
            ['extension' => '.online', 'label' => 'Domain .ONLINE', 'price' => 545000, 'compare_price' => 759000, 'sort_order' => 11],
            ['extension' => '.shop', 'label' => 'Domain .SHOP', 'price' => 655000, 'compare_price' => 899000, 'sort_order' => 12],
            ['extension' => '.store', 'label' => 'Domain .STORE', 'price' => 720000, 'compare_price' => 950000, 'sort_order' => 13],
            ['extension' => '.xyz', 'label' => 'Domain .XYZ', 'price' => 89000, 'compare_price' => 129000, 'sort_order' => 14],
            ['extension' => '.info', 'label' => 'Domain .INFO', 'price' => 95000, 'compare_price' => 140000, 'sort_order' => 15],
        ];

        foreach ($extensions as $ext) {
            DomainExtension::query()->updateOrCreate(
                ['extension' => $ext['extension']],
                array_merge($ext, ['is_active' => true]),
            );
        }

        $packages = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Hosting + SSL + 1 email bisnis',
                'price' => 500000,
                'billing_period' => 'yearly',
                'features' => ['Hosting 5 GB', 'SSL Gratis', '1 Email Bisnis', 'Support Email'],
                'sort_order' => 1,
                'is_featured' => false,
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Paket populer untuk UMKM & company profile',
                'price' => 1200000,
                'billing_period' => 'yearly',
                'features' => ['Hosting 20 GB', 'SSL Gratis', '5 Email Bisnis', 'Backup Mingguan', 'Support WhatsApp'],
                'sort_order' => 2,
                'is_featured' => true,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'Performa tinggi untuk e-commerce & instansi',
                'price' => 2500000,
                'billing_period' => 'yearly',
                'features' => ['Hosting 50 GB', 'SSL Gratis', 'Email Unlimited', 'Backup Harian', 'Priority Support'],
                'sort_order' => 3,
                'is_featured' => false,
            ],
        ];

        foreach ($packages as $pkg) {
            WebsitePackage::query()->updateOrCreate(
                ['slug' => $pkg['slug']],
                array_merge($pkg, ['is_active' => true]),
            );
        }

        $this->linkServicesToTemplates();
    }

    /**
     * Template dengan live_demo_url yang sudah dicek HTTP 200 (Juni 2026).
     * URL gagal / duplikat tidak dimasukkan.
     *
     * @return list<array<string, mixed>>
     */
    private function verifiedTemplates(): array
    {
        $rows = [
            // Produk Ekspor
            ['name' => 'Logistica', 'category' => 'produk-ekspor', 'url' => 'https://technext.github.io/logistica/', 'featured' => true],
            ['name' => 'Cargo', 'category' => 'produk-ekspor', 'url' => 'https://themewagon.github.io/cargo/'],

            // Agrikultur
            ['name' => 'Foody', 'category' => 'agrikultur', 'url' => 'https://technext.github.io/foody2/', 'featured' => true],
            ['name' => 'Milky', 'category' => 'agrikultur', 'url' => 'https://technext.github.io/milky/'],
            ['name' => 'Fruitables', 'category' => 'agrikultur', 'url' => 'https://themewagon.github.io/fruitables/'],

            // Craft
            ['name' => 'Furni', 'category' => 'craft', 'url' => 'https://themewagon.github.io/furni/', 'featured' => true],
            ['name' => 'Woody', 'category' => 'craft', 'url' => 'https://technext.github.io/woody/'],

            // Company
            ['name' => 'BizLand', 'category' => 'company', 'url' => 'https://themewagon.github.io/BizLand/', 'featured' => true],
            ['name' => 'Impact', 'category' => 'company', 'url' => 'https://themewagon.github.io/Impact/'],
            ['name' => 'Finanza', 'category' => 'company', 'url' => 'https://technext.github.io/finanza/'],
            ['name' => 'Nova', 'category' => 'company', 'url' => 'https://themewagon.github.io/Nova/'],

            // Otomotif
            ['name' => 'CarServ', 'category' => 'otomotif', 'url' => 'https://technext.github.io/carserv/', 'featured' => true],
            ['name' => 'CarBook', 'category' => 'otomotif', 'url' => 'https://themewagon.github.io/carbook/'],
            ['name' => 'HVAC', 'category' => 'otomotif', 'url' => 'https://technext.github.io/hvac/'],

            // Restaurant
            ['name' => 'Restoran', 'category' => 'restaurant', 'url' => 'https://technext.github.io/restoran/', 'featured' => true],
            ['name' => 'Feane', 'category' => 'restaurant', 'url' => 'https://themewagon.github.io/feane/'],
            ['name' => 'CakeZone', 'category' => 'restaurant', 'url' => 'https://technext.github.io/cakezone/'],
            ['name' => 'Tasty', 'category' => 'restaurant', 'url' => 'https://themewagon.github.io/tasty/'],

            // Service / Jasa
            ['name' => 'Plumber', 'category' => 'service-jasa', 'url' => 'https://technext.github.io/plumber/', 'featured' => true],
            ['name' => 'Securex', 'category' => 'service-jasa', 'url' => 'https://technext.github.io/securex/'],
            ['name' => 'Apex', 'category' => 'service-jasa', 'url' => 'https://technext.github.io/apex/'],

            // Website UMKM
            ['name' => 'HairCut', 'category' => 'umkm', 'url' => 'https://technext.github.io/haircut/', 'featured' => true],
            ['name' => 'Baker', 'category' => 'umkm', 'url' => 'https://technext.github.io/baker/'],
            ['name' => 'Laundry', 'category' => 'umkm', 'url' => 'https://technext.github.io/laundry/'],

            // Toko Online
            ['name' => 'Zay Shop', 'category' => 'toko-online', 'url' => 'https://themewagon.github.io/zay-shop/', 'featured' => true],
            ['name' => 'Eshopper', 'category' => 'toko-online', 'url' => 'https://technext.github.io/eshopper/'],
            ['name' => 'Karl Fashion', 'category' => 'toko-online', 'url' => 'https://themewagon.github.io/karl/'],
            ['name' => 'Violeta', 'category' => 'toko-online', 'url' => 'https://themewagon.github.io/violet/'],

            // Web Sekolah
            ['name' => 'Coaching', 'category' => 'sekolah', 'url' => 'https://themewagon.github.io/coaching/', 'featured' => true],
            ['name' => 'Courses', 'category' => 'sekolah', 'url' => 'https://themewagon.github.io/courses/'],
        ];

        $prices = [
            'produk-ekspor' => [2800000, 2400000],
            'agrikultur' => [2200000, 1890000],
            'craft' => [1800000, 1500000],
            'company' => [2500000, 2100000],
            'otomotif' => [2900000, 2500000],
            'restaurant' => [1900000, 1590000],
            'service-jasa' => [2300000, 1990000],
            'umkm' => [1500000, 1200000],
            'toko-online' => [3200000, 2750000],
            'sekolah' => [2500000, 2100000],
        ];

        $downloads = 40;
        $templates = [];

        foreach ($rows as $row) {
            [$price, $sale] = $prices[$row['category']];
            $downloads += 3;

            $templates[] = [
                'name' => $row['name'],
                'slug' => Str::slug($row['name']),
                'category' => $row['category'],
                'short_description' => "Template {$row['name']} — desain responsif siap pakai untuk bisnis Anda.",
                'live_demo_url' => $row['url'],
                'price' => $price,
                'sale_price' => $sale,
                'is_featured' => $row['featured'] ?? false,
                'downloads_count' => $downloads,
            ];
        }

        return $templates;
    }

    private function linkServicesToTemplates(): void
    {
        // Template marketplace terhubung lewat halaman Cari Design, bukan record Service bidang layanan.
    }
}
