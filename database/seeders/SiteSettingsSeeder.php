<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Services\Settings\SiteSettingsService;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'promo_enabled' => '1',
            'promo_text' => 'CV. Laksana Solution — Konsultan IT & Pengadaan Teknologi',
            'promo_link_label' => 'Konsultasi gratis via WhatsApp →',
            'brand_logo_path' => '',
            'footer_tagline' => 'Platform pembuatan website untuk UMKM, sekolah, dan instansi. Bikin website profesional tanpa ribet — oleh CV. Laksana Solution.',
            'footer_copyright' => 'CV. Laksana Solution',
            'whatsapp_number' => config('laksana.brand.whatsapp', '6285212455505'),
            'contact_phone' => config('laksana.brand.phone', '085212455505'),
            'whatsapp_float_enabled' => '1',
            'whatsapp_float_message' => 'Halo, saya ingin konsultasi pembuatan website.',
            'facebook_url' => '',
            'instagram_url' => '',
            'linkedin_url' => '',
            'cta_enabled' => '1',
            'cta_title' => 'Siap Bikin Website Tanpa Ribet?',
            'cta_subtitle' => 'Mulai dari template siap pakai, lengkap domain & hosting. Website aktif 2×24 jam.',
            'cta_button_label' => 'Buat Website Sekarang',
            'cta_button_url' => '/cari-design',
            'design_badge' => 'KOLEKSI · DESAIN',
            'design_title' => 'Pilih desain yang siap kamu pakai.',
            'design_subtitle' => 'Ratusan template website profesional untuk UMKM, company profile, toko online, sekolah, dan instansi — SEO & mobile friendly.',
            'design_sidebar_note' => 'BISA GANTI KAPAN SAJA',
            'design_sidebar_text' => 'Pilih template sekarang, ganti gratis kapan pun setelah website aktif.',
        ];

        foreach ($defaults as $key => $value) {
            SiteSetting::query()->updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'group' => str_starts_with($key, 'promo_') ? 'promo' : (str_starts_with($key, 'design_') ? 'design' : 'general'),
                ],
            );
        }

        app(SiteSettingsService::class)->flush();
    }
}
