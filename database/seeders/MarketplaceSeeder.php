<?php

namespace Database\Seeders;

use App\Models\DomainExtension;
use App\Models\Template;
use App\Models\TemplateCategory;
use App\Models\WebsitePackage;
use Illuminate\Database\Seeder;

class MarketplaceSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['slug' => 'umkm', 'name' => 'UMKM', 'sort_order' => 1],
            ['slug' => 'company', 'name' => 'Company', 'sort_order' => 2],
            ['slug' => 'toko-online', 'name' => 'Toko Online', 'sort_order' => 3],
            ['slug' => 'sekolah', 'name' => 'Sekolah', 'sort_order' => 4],
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $categoryIds[$cat['slug']] = TemplateCategory::query()->updateOrCreate(
                ['slug' => $cat['slug']],
                array_merge($cat, ['is_active' => true]),
            )->id;
        }

        $templates = [
            // UMKM
            [
                'name' => 'UMKM Modern — Katalog Produk',
                'slug' => 'umkm-modern',
                'category' => 'umkm',
                'short_description' => 'Template website UMKM responsif dengan katalog produk & WhatsApp order.',
                'price' => 1500000,
                'sale_price' => 1200000,
                'is_featured' => true,
                'downloads_count' => 124,
            ],
            [
                'name' => 'UMKM Premium Storefront',
                'slug' => 'umkm-premium-storefront',
                'category' => 'umkm',
                'short_description' => 'Landing page UMKM dengan testimoni, FAQ, dan form pemesanan.',
                'price' => 1800000,
                'sale_price' => 1500000,
                'is_featured' => false,
                'downloads_count' => 88,
            ],
            [
                'name' => 'Warung Digital — Menu & Promo',
                'slug' => 'warung-digital-menu',
                'category' => 'umkm',
                'short_description' => 'Website warung/kuliner dengan menu digital, promo harian, dan order WA.',
                'price' => 1400000,
                'sale_price' => 1100000,
                'is_featured' => false,
                'downloads_count' => 67,
            ],
            [
                'name' => 'Jasa Lokal — Booking Online',
                'slug' => 'jasa-lokal-booking',
                'category' => 'umkm',
                'short_description' => 'Untuk salon, bengkel, laundry — jadwal layanan & booking online.',
                'price' => 1600000,
                'sale_price' => null,
                'is_featured' => false,
                'downloads_count' => 54,
            ],
            // Company
            [
                'name' => 'Company Profile Pro',
                'slug' => 'company-profile-pro',
                'category' => 'company',
                'short_description' => 'Profil perusahaan elegan dengan halaman layanan, tim, dan portofolio.',
                'price' => 2000000,
                'sale_price' => null,
                'is_featured' => true,
                'downloads_count' => 98,
            ],
            [
                'name' => 'Corporate Blue — Instansi & B2B',
                'slug' => 'corporate-blue',
                'category' => 'company',
                'short_description' => 'Desain corporate untuk instansi, BUMN, dan layanan profesional.',
                'price' => 3200000,
                'sale_price' => null,
                'is_featured' => false,
                'downloads_count' => 45,
            ],
            [
                'name' => 'Konsultan Profesional',
                'slug' => 'konsultan-profesional',
                'category' => 'company',
                'short_description' => 'Website firma konsultan hukum, akuntansi, atau bisnis dengan case study.',
                'price' => 2800000,
                'sale_price' => 2400000,
                'is_featured' => false,
                'downloads_count' => 38,
            ],
            [
                'name' => 'Startup Launch Page',
                'slug' => 'startup-launch-page',
                'category' => 'company',
                'short_description' => 'Landing page startup dengan hero kuat, fitur produk, dan waitlist.',
                'price' => 1900000,
                'sale_price' => 1590000,
                'is_featured' => true,
                'downloads_count' => 112,
            ],
            // Toko Online
            [
                'name' => 'Toko Online Lite — Checkout Penuh',
                'slug' => 'toko-online-lite',
                'category' => 'toko-online',
                'short_description' => 'E-commerce dengan keranjang, checkout, dan integrasi pembayaran.',
                'price' => 3500000,
                'sale_price' => 2990000,
                'is_featured' => true,
                'downloads_count' => 156,
            ],
            [
                'name' => 'Fashion Boutique Store',
                'slug' => 'fashion-boutique-store',
                'category' => 'toko-online',
                'short_description' => 'Toko fashion dengan filter ukuran/warna, lookbook, dan promo diskon.',
                'price' => 3200000,
                'sale_price' => 2750000,
                'is_featured' => false,
                'downloads_count' => 91,
            ],
            [
                'name' => 'Digital Product Store',
                'slug' => 'digital-product-store',
                'category' => 'toko-online',
                'short_description' => 'Jual e-book, template, atau kursus digital dengan download otomatis.',
                'price' => 2900000,
                'sale_price' => null,
                'is_featured' => false,
                'downloads_count' => 73,
            ],
            [
                'name' => 'Marketplace Multi Seller Lite',
                'slug' => 'marketplace-multi-seller',
                'category' => 'toko-online',
                'short_description' => 'Platform multi-vendor sederhana — banyak penjual, satu checkout.',
                'price' => 4500000,
                'sale_price' => 3990000,
                'is_featured' => true,
                'downloads_count' => 42,
            ],
            // Sekolah
            [
                'name' => 'Sekolah Digital — PPDB & Berita',
                'slug' => 'sekolah-digital',
                'category' => 'sekolah',
                'short_description' => 'Website sekolah dengan berita, PPDB online, dan galeri kegiatan.',
                'price' => 2500000,
                'sale_price' => null,
                'is_featured' => false,
                'downloads_count' => 72,
            ],
            [
                'name' => 'Madrasah Modern',
                'slug' => 'madrasah-modern',
                'category' => 'sekolah',
                'short_description' => 'Profil madrasah/pondok dengan program tahfidz, prestasi, dan pendaftaran.',
                'price' => 2300000,
                'sale_price' => 1990000,
                'is_featured' => false,
                'downloads_count' => 48,
            ],
            [
                'name' => 'Kampus & PMB Online',
                'slug' => 'kampus-pmb-online',
                'category' => 'sekolah',
                'short_description' => 'Website kampus/perguruan tinggi dengan info jurusan dan PMB online.',
                'price' => 3800000,
                'sale_price' => null,
                'is_featured' => true,
                'downloads_count' => 35,
            ],
            [
                'name' => 'Bimbel & Kursus Online',
                'slug' => 'bimbel-kursus-online',
                'category' => 'sekolah',
                'short_description' => 'Landing bimbel dengan paket kelas, jadwal, testimoni siswa, dan daftar online.',
                'price' => 2100000,
                'sale_price' => 1750000,
                'is_featured' => false,
                'downloads_count' => 61,
            ],
        ];

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
                    'tech_stack' => 'Laravel, Tailwind',
                    'is_featured' => $data['is_featured'],
                    'is_active' => true,
                    'downloads_count' => $data['downloads_count'],
                ],
            );
        }

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

    private function linkServicesToTemplates(): void
    {
        // Template marketplace terhubung lewat halaman Cari Design, bukan record Service bidang layanan.
    }
}
