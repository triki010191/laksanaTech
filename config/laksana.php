<?php

return [
    'brand' => [
        'name' => env('APP_BRAND_NAME', 'laksanatech'),
        'legal_name' => 'CV. Laksana Solution',
        'company' => env('APP_COMPANY_NAME', 'CV. Laksana Solution'),
        'tagline' => 'Solusi Digital & Pengadaan Teknologi Terpercaya',
        'email' => env('APP_COMPANY_EMAIL', 'laksanasolution@gmail.com'),
        'phone' => env('APP_COMPANY_PHONE', '085212455505'),
        'whatsapp' => env('APP_COMPANY_WHATSAPP', '6285212455505'),
        'address' => 'Jl. Raya Mandalawangi Km. 13, Desa Cikoneng, Kec. Mandalawangi, Kab. Pandeglang, Banten',
        'director' => 'Triki Maselanjana',
        'colors' => [
            'primary' => '#00AEEF',
            'secondary' => '#A6CE39',
            'accent' => '#F9B233',
            'background' => '#FFFFFF',
        ],
        'logos' => [
            'main' => 'images/brand/logo-main.png',
            'navbar' => 'images/brand/logo-main.png',
            'icon' => 'images/brand/logo-main.png',
            'horizontal_light' => 'images/brand/logo-main.png',
            'horizontal_dark' => 'images/brand/logo-main.png',
        ],
    ],

    'payment' => [
        'default_gateway' => env('PAYMENT_DEFAULT_GATEWAY', 'xendit'),
        'currency' => env('PAYMENT_CURRENCY', 'IDR'),
    ],

    'hero_slides' => [
        [
            'badge' => 'Mitra Digital Terpercaya',
            'title' => 'Buat website & aplikasi profesional,',
            'emphasis' => 'tumbuh bersama bisnis Anda.',
            'description' => 'Layanan pembuatan website, aplikasi custom, template siap pakai, dan pengadaan IT dari CV. Laksana Solution.',
            'image' => 'images/hero/slide-1.png',
            'cta_primary' => ['label' => 'Mulai Proyek', 'route' => 'contact'],
            'cta_secondary' => ['label' => 'Lihat Portofolio', 'route' => 'portfolio'],
        ],
        [
            'badge' => 'SEO & Performa Optimal',
            'title' => 'Website yang mudah ditemukan,',
            'emphasis' => 'siap bersaing di Google.',
            'description' => 'Desain modern, mobile-first, dan teroptimasi agar pelanggan menemukan layanan Anda lebih cepat.',
            'image' => 'images/hero/slide-2.png',
            'cta_primary' => ['label' => 'Konsultasi Gratis', 'route' => 'contact'],
            'cta_secondary' => ['label' => 'Hubungi WhatsApp', 'url' => 'https://wa.me/'.env('APP_COMPANY_WHATSAPP', '6285212455505')],
        ],
        [
            'badge' => 'UMKM · Sekolah · Instansi',
            'title' => 'Solusi digital lengkap,',
            'emphasis' => 'dari ide hingga online.',
            'description' => 'Company profile, e-commerce, sistem informasi, CCTV, jaringan, dan pengadaan perangkat teknologi.',
            'image' => 'images/hero/slide-3.png',
            'cta_primary' => ['label' => 'Lihat Layanan', 'route' => 'services'],
            'cta_secondary' => ['label' => 'Chat WhatsApp', 'url' => 'https://wa.me/'.env('APP_COMPANY_WHATSAPP', '6285212455505')],
        ],
    ],

    'contact' => [
        'notify_email' => env('CONTACT_NOTIFY_EMAIL', env('APP_COMPANY_EMAIL', 'laksanasolution@gmail.com')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Layanan — terstruktur tanpa duplikasi produk
    | marketplace = alur pesan website (template, domain, checkout)
    | bidang = layanan IT & pengadaan CV. Laksana Solution
    |--------------------------------------------------------------------------
    */
    'layanan_menu' => [
        'marketplace' => [
            'label' => 'Website Siap Pakai',
            'items' => [
                [
                    'title' => 'Cari Design / Template',
                    'description' => 'Mulai dari desain — pilih template, lalu domain & checkout',
                    'route' => 'designs',
                    'icon' => 'layout',
                ],
                [
                    'title' => 'Paket Domain & Hosting',
                    'description' => 'Mulai dari domain — cari domain, pilih template & checkout',
                    'route' => 'packages',
                    'icon' => 'globe',
                ],
            ],
        ],
        'bidang' => [
            'label' => 'Layanan',
            'items' => [
                [
                    'title' => 'Keamanan & Akses',
                    'slug' => 'keamanan-dan-akses',
                    'icon' => 'camera',
                    'audience' => 'Untuk gedung & fasilitas',
                    'summary' => 'Instalasi CCTV dan sistem pintu akses untuk gedung, mall, kantor, dan fasilitas usaha.',
                ],
                [
                    'title' => 'Infrastruktur Jaringan',
                    'slug' => 'infrastruktur-jaringan',
                    'icon' => 'network',
                    'audience' => 'Untuk operasional IT',
                    'summary' => 'Jaringan LAN/WAN, WiFi, server lokal, dan maintenance IT harian.',
                ],
                [
                    'title' => 'Pengadaan IT & Instansi',
                    'slug' => 'pengadaan-it-instansi',
                    'icon' => 'procurement',
                    'audience' => 'Untuk sekolah & pemerintah',
                    'summary' => 'Pengadaan perangkat IT, SIPLah, dan kebutuhan instansi pemerintah.',
                ],
                [
                    'title' => 'Pesanan Khusus',
                    'slug' => 'pesanan-khusus',
                    'icon' => 'sparkle',
                    'audience' => 'Untuk kebutuhan custom',
                    'summary' => 'Sistem custom 100% sesuai kebutuhan: aplikasi, ERP, integrasi AI, mobile app, POS & SIM.',
                ],
            ],
        ],
    ],

    'home' => [
        'section_01' => [
            'eyebrow' => '01 · Koleksi Desain',
            'title' => 'Solusi <em>siap pakai</em> untuk bisnis Anda',
            'lead' => 'Pilih template website profesional — filter menurut jenis bisnis di halaman Cari Design. Checkout online, website siap 2×24 jam.',
            'cta' => 'Lihat Semua Desain',
            'cta_route' => 'designs',
        ],
        'section_02' => [
            'eyebrow' => '02 · Layanan',
            'title' => 'Pilih layanan <em>paling pas</em> untuk bisnismu',
            'lead' => 'Tidak semua bisnis butuh solusi yang sama. Pilih layanan yang sesuai kebutuhan dan tujuan bisnismu.',
            'cta' => 'Lihat Semua Layanan',
            'cta_route' => 'services',
        ],
        'section_03' => [
            'eyebrow' => '03 · Paket Domain',
            'title' => 'Harga pas di kantong, website <em>kelas profesional</em>',
            'lead' => 'Membuat website profesional kini lebih terjangkau. Setiap paket sudah termasuk hosting, SSL, dan dukungan teknis.',
            'cta' => 'Lihat Paket Lengkap',
            'cta_route' => 'packages',
            'promo_code' => 'WEBSITEJUARA',
        ],
        'section_04' => [
            'eyebrow' => '04 · Suara Pengguna',
            'title' => '12.000+ UMKM telah <em>bergabung.</em>',
            'lead' => 'Mereka mempercayakan website bisnisnya pada kami. Kini saatnya giliran Anda.',
            'stats' => [
                ['index' => '01 / 03', 'value' => '500+', 'label' => 'Proyek digital selesai'],
                ['index' => '02 / 03', 'value' => '16+', 'label' => 'Portofolio unggulan'],
                ['index' => '03 / 03', 'value' => '4.9 / 5', 'label' => 'Rating kepuasan klien'],
            ],
        ],
    ],

    'domain_offers' => [
        [
            'extension_key' => '.com',
            'title' => 'Buat Go Global',
            'description' => 'Cocok untuk UMKM yang ingin menjangkau pasar domestik & internasional.',
            'features' => ['Domain global .com', 'Hosting & SSL', 'Dukungan teknis'],
        ],
        [
            'extension_key' => '.co.id',
            'title' => 'Buat Perusahaan',
            'description' => 'Ideal untuk PT, CV, dan badan usaha berbadan hukum di Indonesia.',
            'features' => ['Domain .co.id resmi', 'Hosting & SSL', 'Dukungan teknis'],
        ],
        [
            'extension_key' => '.id',
            'title' => 'Buat Bisnis Lokal',
            'description' => 'Untuk brand lokal yang ingin dilihat profesional di Indonesia.',
            'features' => ['Domain .id lokal', 'Hosting & SSL', 'Dukungan teknis'],
        ],
    ],

    'tutorial' => [
        'hero_title' => 'Dua cara mudah pesan website.',
        'hero_subtitle' => 'Pilih alur yang paling cocok: mulai dari pencarian domain atau jelajahi template desain dulu. Keduanya berakhir di checkout yang sama — data, paket, dan pembayaran.',
        'estimate_label' => 'Estimasi waktu',
        'estimate_text' => 'Website kamu siap dalam waktu maksimal 2×24 jam setelah pembayaran.',
        'methods' => [
            [
                'number' => '01',
                'label' => 'Cara Pertama',
                'title' => 'Mulai dari domain.',
                'description' => 'Cari nama domain terlebih dahulu, pilih template, isi data, lalu selesaikan pembayaran. Cocok jika kamu sudah punya nama brand atau ingin memastikan domain tersedia dulu.',
                'flow' => 'domain_first',
                'panel_title' => 'Cari domain dulu',
                'cta_label' => 'Mulai dari domain',
                'steps' => [
                    ['num' => 1, 'title' => 'Cari domain kamu', 'icon' => 'search'],
                    ['num' => 2, 'title' => 'Pilih ekstensi yang cocok', 'icon' => 'globe'],
                    ['num' => 3, 'title' => 'Pilih template website', 'icon' => 'layout'],
                    ['num' => 4, 'title' => 'Lengkapi data', 'icon' => 'form'],
                    ['num' => 5, 'title' => 'Pilih paket pembayaran', 'icon' => 'package'],
                    ['num' => 6, 'title' => 'Selesaikan pembayaran', 'icon' => 'payment'],
                    ['num' => 7, 'title' => 'Tunggu 2×24 jam — website siap!', 'icon' => 'done'],
                ],
            ],
            [
                'number' => '02',
                'label' => 'Cara Kedua',
                'title' => 'Mulai dari template.',
                'description' => 'Telusuri koleksi desain template terlebih dahulu, pilih yang paling sesuai dengan bisnismu, lalu lanjutkan dengan domain, data, dan pembayaran. Cocok kalau kamu ingin lihat tampilan dulu sebelum memutuskan.',
                'flow' => 'template_first',
                'panel_title' => 'Cari template dulu',
                'cta_label' => 'Mulai dari template',
                'steps' => [
                    ['num' => 1, 'title' => 'Pilih template website', 'icon' => 'layout'],
                    ['num' => 2, 'title' => 'Pilih ekstensi domain', 'icon' => 'globe'],
                    ['num' => 3, 'title' => 'Cari & cek domain', 'icon' => 'search'],
                    ['num' => 4, 'title' => 'Lengkapi data', 'icon' => 'form'],
                    ['num' => 5, 'title' => 'Pilih paket pembayaran', 'icon' => 'package'],
                    ['num' => 6, 'title' => 'Selesaikan pembayaran', 'icon' => 'payment'],
                    ['num' => 7, 'title' => 'Tunggu 2×24 jam — website siap!', 'icon' => 'done'],
                ],
            ],
        ],
        'cta' => [
            'badge' => 'Siap Mulai',
            'title' => 'Pilih jalur dan bikin website kamu sekarang.',
            'title_emphasis' => 'bikin website',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Checkout website — domain, template, data, paket & bayar
    | Domain API: set DOMAIN_LOOKUP_DRIVER=resellerclub + credentials saat siap.
    | Reseller populer: ResellerClub, OpenSRS, Rumahweb API, IDCloudHost.
    |--------------------------------------------------------------------------
    */
    'checkout' => [
        'admin_fee' => 50000,
        'editing_addon_price' => 500000,
        'default_promo' => 'WEBSITEJUARA',
        'subscription_plans' => [
            [
                'years' => 1,
                'label' => '1 Tahun',
                'tagline' => 'Termasuk domain, hosting & template web',
                'hosting_price' => 700000,
                'features' => ['Domain 1 tahun', 'Hosting unlimited', 'SSL gratis', 'Email bisnis'],
                'recommended' => false,
            ],
            [
                'years' => 2,
                'label' => '2 Tahun',
                'tagline' => 'Hemat lebih banyak untuk bisnis jangka menengah',
                'hosting_price' => 1547000,
                'features' => ['Domain 2 tahun', 'Hosting unlimited', 'SSL gratis', 'Email bisnis', 'Hemat lebih'],
                'recommended' => true,
            ],
            [
                'years' => 3,
                'label' => '3 Tahun',
                'tagline' => 'Paling hemat untuk komitmen jangka panjang',
                'hosting_price' => 2341000,
                'features' => ['Domain 3 tahun', 'Hosting unlimited', 'SSL gratis', 'Email bisnis', 'Prioritas support'],
                'recommended' => false,
            ],
        ],
        'steps' => [
            ['key' => 'domain', 'num' => '01', 'title' => 'Domain', 'hint' => 'Cari nama domain yang pas untuk bisnismu.'],
            ['key' => 'template', 'num' => '02', 'title' => 'Template', 'hint' => 'Pilih desain yang mewakili brand.'],
            ['key' => 'data', 'num' => '03', 'title' => 'Data Diri', 'hint' => 'Untuk aktivasi domain.'],
            ['key' => 'package', 'num' => '04', 'title' => 'Paket & Bayar', 'hint' => 'Pilih durasi dan selesaikan pembayaran.'],
        ],
    ],

    'seo' => [
        'default_title' => 'laksanatech — Solusi Website, Aplikasi & Pengadaan IT',
        'default_description' => 'laksanatech oleh CV. Laksana Solution: jasa website, aplikasi custom, template marketplace, dan pengadaan IT profesional.',
        'twitter_handle' => env('SEO_TWITTER_HANDLE'),
    ],
];
