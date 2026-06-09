<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['slug' => 'website', 'name' => 'Website', 'sort_order' => 1],
            ['slug' => 'aplikasi-sistem', 'name' => 'Aplikasi & Sistem', 'sort_order' => 2],
            ['slug' => 'pos-inventory', 'name' => 'POS & Inventory', 'sort_order' => 3],
            ['slug' => 'instalasi', 'name' => 'Instalasi IT', 'sort_order' => 4],
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $categoryIds[$cat['slug']] = PortfolioCategory::query()->updateOrCreate(
                ['slug' => $cat['slug']],
                array_merge($cat, ['is_active' => true]),
            )->id;
        }

        $projects = [
            [
                'title' => 'Website SMAN 2 Pandeglang',
                'category' => 'website',
                'client_name' => 'SMAN 2 Pandeglang',
                'short_description' => 'Website resmi sekolah menengah atas negeri dengan informasi akademik, berita, dan profil instansi.',
                'technologies' => ['PHP', 'MySQL', 'Responsive Design'],
                'is_featured' => true,
            ],
            [
                'title' => 'Website MAN 1 Kota Cilegon',
                'category' => 'website',
                'client_name' => 'MAN 1 Kota Cilegon',
                'short_description' => 'Portal website madrasah aliyah negeri untuk publikasi kegiatan, PPDB, dan informasi sekolah.',
                'technologies' => ['PHP', 'MySQL', 'SEO'],
                'is_featured' => true,
            ],
            [
                'title' => 'Website Firma Hukum Rantai Khatulistiwa',
                'category' => 'website',
                'client_name' => 'Firma Hukum Rantai Khatulistiwa',
                'short_description' => 'Company profile firma hukum profesional dengan layanan, tim advokat, dan formulir konsultasi.',
                'technologies' => ['PHP', 'Tailwind', 'MySQL'],
                'is_featured' => false,
            ],
            [
                'title' => 'Aplikasi Pendataan Kasus Narkoba Polres Pandeglang',
                'category' => 'aplikasi-sistem',
                'client_name' => 'Polres Pandeglang',
                'short_description' => 'Sistem informasi pendataan dan pelaporan kasus narkoba untuk kepolisian resor Pandeglang.',
                'technologies' => ['PHP', 'MySQL', 'Laravel'],
                'is_featured' => true,
            ],
            [
                'title' => 'Website E-Commerce Domykado',
                'category' => 'website',
                'client_name' => 'Domykado',
                'short_description' => 'Toko online e-commerce dengan katalog produk, keranjang belanja, dan integrasi pembayaran.',
                'technologies' => ['PHP', 'MySQL', 'Payment Gateway'],
                'is_featured' => true,
            ],
            [
                'title' => 'POS Apotek Cimanuk',
                'category' => 'pos-inventory',
                'client_name' => 'Apotek Cimanuk',
                'short_description' => 'Point of Sale apotek dengan manajemen stok obat, resep, dan laporan penjualan harian.',
                'technologies' => ['PHP', 'MySQL', 'POS'],
                'is_featured' => false,
            ],
            [
                'title' => 'POS Apotek Central Farma 5',
                'category' => 'pos-inventory',
                'client_name' => 'Central Farma 5',
                'short_description' => 'Sistem kasir dan inventory apotek cabang dengan kontrol stok dan laporan keuangan.',
                'technologies' => ['PHP', 'MySQL', 'POS'],
                'is_featured' => true,
            ],
            [
                'title' => 'Inventory dan POS PBF Central',
                'category' => 'pos-inventory',
                'client_name' => 'PBF Central',
                'short_description' => 'Sistem inventory dan POS untuk Pedagang Besar Farmasi dengan tracking batch dan distribusi.',
                'technologies' => ['PHP', 'MySQL', 'Inventory'],
                'is_featured' => false,
            ],
            [
                'title' => 'Aplikasi Uang Pas Rutan Pandeglang',
                'category' => 'aplikasi-sistem',
                'client_name' => 'Rutan Pandeglang',
                'short_description' => 'Aplikasi pengelolaan uang pas dan transaksi warga binaan pemasyarakatan.',
                'technologies' => ['PHP', 'MySQL'],
                'is_featured' => false,
            ],
            [
                'title' => 'Sistem Pengiriman PT. Palnas Kota Djawai',
                'category' => 'aplikasi-sistem',
                'client_name' => 'PT. Palnas Kota Djawai',
                'short_description' => 'Sistem manajemen pengiriman barang dengan tracking order, driver, dan laporan operasional.',
                'technologies' => ['PHP', 'MySQL', 'Logistics'],
                'is_featured' => false,
            ],
            [
                'title' => 'Sistem Penjualan Perumahan PT. Hasil Karya Putra Gemilang',
                'category' => 'aplikasi-sistem',
                'client_name' => 'PT. Hasil Karya Putra Gemilang',
                'short_description' => 'Sistem penjualan unit perumahan dengan booking, cicilan, dan manajemen kavling.',
                'technologies' => ['PHP', 'MySQL', 'CRM'],
                'is_featured' => false,
            ],
            [
                'title' => 'Sistem Koperasi Warga Pengajar',
                'category' => 'aplikasi-sistem',
                'client_name' => 'Koperasi Warga Pengajar',
                'short_description' => 'Aplikasi koperasi simpan pinjam untuk warga pengajar dengan modul anggota, simpanan, dan pinjaman.',
                'technologies' => ['PHP', 'MySQL'],
                'is_featured' => false,
            ],
            [
                'title' => 'Sistem Pengiriman Tangerang Fast',
                'category' => 'aplikasi-sistem',
                'client_name' => 'Tangerang Fast',
                'short_description' => 'Platform logistik pengiriman dengan manajemen kurir, rute, dan status pengantaran real-time.',
                'technologies' => ['PHP', 'MySQL', 'GPS Tracking'],
                'is_featured' => true,
            ],
            [
                'title' => 'Website dan Portal Aplikasi Kilau Group',
                'category' => 'website',
                'client_name' => 'Kilau Group',
                'short_description' => 'Website perusahaan terintegrasi portal aplikasi internal untuk operasional bisnis grup.',
                'technologies' => ['PHP', 'MySQL', 'Portal'],
                'is_featured' => false,
            ],
            [
                'title' => 'Sistem Kantin Foodcourt Pamulang',
                'category' => 'aplikasi-sistem',
                'client_name' => 'Foodcourt Pamulang',
                'short_description' => 'Sistem kasir dan manajemen tenant kantin foodcourt dengan laporan penjualan per merchant.',
                'technologies' => ['PHP', 'MySQL', 'POS'],
                'is_featured' => false,
            ],
            [
                'title' => 'Instalasi Access Door PT. AEON Mall',
                'category' => 'instalasi',
                'client_name' => 'PT. AEON Mall',
                'short_description' => 'Instalasi dan konfigurasi sistem access door / pintu akses keamanan untuk retail mall.',
                'technologies' => ['Access Control', 'CCTV', 'Hardware'],
                'is_featured' => true,
            ],
        ];

        foreach ($projects as $index => $project) {
            Portfolio::query()->updateOrCreate(
                ['slug' => Str::slug($project['title'])],
                [
                    'portfolio_category_id' => $categoryIds[$project['category']],
                    'title' => $project['title'],
                    'client_name' => $project['client_name'],
                    'short_description' => $project['short_description'],
                    'technologies' => $project['technologies'],
                    'is_featured' => $project['is_featured'],
                    'is_published' => true,
                    'sort_order' => $index + 1,
                    'completed_at' => now()->subMonths(max(1, 16 - $index))->startOfMonth(),
                ],
            );
        }
    }
}
