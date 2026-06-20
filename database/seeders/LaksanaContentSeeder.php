<?php

namespace Database\Seeders;

use App\Enums\ServiceType;
use App\Models\BlogCategory;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\TemplateCategory;
use Illuminate\Database\Seeder;

class LaksanaContentSeeder extends Seeder
{
    /** @var list<string> */
    private array $obsoleteCategorySlugs = [
        'layanan-website',
        'pengembangan-website',
        'pengembangan-aplikasi-custom',
        'sistem-informasi-manajemen',
        'pos-dan-inventory-system',
        'toko-online-e-commerce',
        'website-sekolah-dan-instansi',
        'instalasi-cctv',
        'instalasi-access-door',
        'pengadaan-barang-dan-jasa',
        'pengadaan-siplah-dan-instansi-pemerintah',
    ];

    /** @var list<string> */
    private array $obsoleteServiceSlugs = [
        'layanan-website',
        'website-company-profile',
        'website-toko-online',
        'website-sekolah-instansi',
        'pengembangan-website',
        'pengembangan-aplikasi-custom',
        'sistem-informasi-manajemen',
        'pos-dan-inventory-system',
        'toko-online-e-commerce',
        'website-sekolah-dan-instansi',
        'instalasi-cctv',
        'instalasi-access-door',
        'pengadaan-barang-jasa',
        'pengadaan-siplah-instansi',
        'pengadaan-barang-dan-jasa',
        'pengadaan-siplah-dan-instansi-pemerintah',
    ];

    public function run(): void
    {
        BlogCategory::query()->firstOrCreate(
            ['slug' => 'teknologi'],
            ['name' => 'Teknologi', 'description' => 'Artikel seputar IT dan digital'],
        );

        TemplateCategory::query()->firstOrCreate(
            ['slug' => 'website'],
            ['name' => 'Website', 'sort_order' => 1],
        );

        $bidang = config('laksana.layanan_menu.bidang.items', []);

        $serviceMeta = [
            'keamanan-dan-akses' => [
                'type' => ServiceType::CustomSoftware,
                'starting_price' => 5000000,
            ],
            'infrastruktur-jaringan' => [
                'type' => ServiceType::CustomSoftware,
                'starting_price' => 4000000,
            ],
            'pengadaan-it-instansi' => [
                'type' => ServiceType::Government,
                'starting_price' => null,
            ],
            'pesanan-khusus' => [
                'type' => ServiceType::CustomSoftware,
                'starting_price' => null,
            ],
        ];

        foreach ($bidang as $index => $item) {
            $slug = $item['slug'];

            $category = ServiceCategory::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $item['title'],
                    'description' => $item['summary'] ?? null,
                    'icon' => $item['icon'] ?? null,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );

            $meta = $serviceMeta[$slug] ?? [
                'type' => ServiceType::CustomSoftware,
                'starting_price' => null,
            ];

            Service::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'service_category_id' => $category->id,
                    'name' => $item['title'],
                    'type' => $meta['type'],
                    'short_description' => $item['summary'] ?? 'Layanan profesional '.$item['title'].' oleh CV. Laksana Solution.',
                    'audience' => $item['audience'] ?? null,
                    'starting_price' => $meta['starting_price'],
                    'is_featured' => true,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ],
            );
        }

        ServiceCategory::query()
            ->whereIn('slug', $this->obsoleteCategorySlugs)
            ->update(['is_active' => false]);

        Service::query()
            ->whereIn('slug', $this->obsoleteServiceSlugs)
            ->update(['is_active' => false]);
    }
}
