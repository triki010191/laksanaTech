<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::query()->where('email', 'admin@laksanatech.id')->first()
            ?? User::query()->first();

        if (! $author) {
            return;
        }

        $categories = [
            ['slug' => 'teknologi', 'name' => 'Teknologi', 'description' => 'Artikel seputar IT dan digital'],
            ['slug' => 'bisnis', 'name' => 'Bisnis & UMKM', 'description' => 'Tips mengembangkan bisnis online'],
            ['slug' => 'tutorial', 'name' => 'Tutorial', 'description' => 'Panduan praktis website & hosting'],
            ['slug' => 'tips-website', 'name' => 'Tips Website', 'description' => 'Optimasi dan best practice website'],
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $categoryIds[$cat['slug']] = BlogCategory::query()->updateOrCreate(
                ['slug' => $cat['slug']],
                ['name' => $cat['name'], 'description' => $cat['description']],
            )->id;
        }

        $tags = ['website', 'domain', 'hosting', 'umkm', 'seo', 'ecommerce', 'ppdb', 'keamanan'];
        $tagIds = [];
        foreach ($tags as $tag) {
            $tagIds[$tag] = BlogTag::query()->updateOrCreate(
                ['slug' => $tag],
                ['name' => ucfirst($tag === 'umkm' ? 'UMKM' : ($tag === 'seo' ? 'SEO' : $tag))],
            )->id;
        }

        $posts = [
            [
                'slug' => 'cara-memilih-domain-untuk-bisnis',
                'title' => 'Cara Memilih Domain untuk Bisnis Anda',
                'category' => 'tips-website',
                'tags' => ['domain', 'umkm', 'website'],
                'excerpt' => 'Panduan singkat memilih ekstensi domain .com, .id, atau .co.id yang tepat untuk brand Anda.',
                'content' => '<p>Domain adalah identitas digital bisnis Anda. Pilih nama yang mudah diingat, pendek, dan relevan dengan brand.</p><p>Untuk bisnis lokal, .co.id atau .id memberi kepercayaan. Untuk pasar global, .com masih pilihan utama. Hindari angka dan tanda hubung berlebihan.</p><p>Di LaksanaTech, Anda bisa cek ketersediaan domain langsung saat checkout website.</p>',
                'featured' => true,
                'views' => 342,
            ],
            [
                'slug' => '5-alasan-umkm-butuh-website-profesional',
                'title' => '5 Alasan UMKM Butuh Website Profesional di 2026',
                'category' => 'bisnis',
                'tags' => ['umkm', 'website'],
                'excerpt' => 'Website bukan lagi luxury — ini aset penjualan 24 jam untuk UMKM Indonesia.',
                'content' => '<p>Pelanggan mencari produk lewat Google sebelum WhatsApp. Website profesional membangun kredibilitas, menampilkan katalog, dan menangkap leads otomatis.</p><p>Dengan template siap pakai LaksanaTech, UMKM bisa go-live dalam hitungan hari tanpa biaya development mahal.</p>',
                'featured' => true,
                'views' => 518,
            ],
            [
                'slug' => 'perbedaan-hosting-shared-dan-vps',
                'title' => 'Perbedaan Hosting Shared vs VPS — Mana yang Cocok?',
                'category' => 'tutorial',
                'tags' => ['hosting', 'website'],
                'excerpt' => 'Shared hosting cukup untuk company profile. VPS untuk traffic tinggi dan e-commerce.',
                'content' => '<p><strong>Shared hosting</strong> ekonomis, cocok untuk website company profile dan UMKM pemula. Resource dibagi dengan pengguna lain.</p><p><strong>VPS</strong> memberi resource dedicated, ideal untuk toko online dengan traffic stabil atau aplikasi custom.</p><p>Paket Business LaksanaTech sudah include SSL, backup, dan support WhatsApp.</p>',
                'featured' => false,
                'views' => 189,
            ],
            [
                'slug' => 'checklist-website-sekolah-ppdb-online',
                'title' => 'Checklist Website Sekolah dengan PPDB Online',
                'category' => 'tutorial',
                'tags' => ['website', 'ppdb'],
                'excerpt' => 'Fitur wajib website sekolah modern: PPDB, berita, galeri, dan kontak orang tua.',
                'content' => '<p>Website sekolah efektif harus punya: halaman profil & visi misi, form PPDB online, berita kegiatan, galeri foto, dan kontak yang jelas.</p><p>Template Sekolah Digital LaksanaTech sudah include struktur PPDB dan modul berita siap pakai.</p>',
                'featured' => false,
                'views' => 267,
            ],
            [
                'slug' => 'tips-seo-dasar-website-baru',
                'title' => 'Tips SEO Dasar untuk Website Baru',
                'category' => 'tips-website',
                'tags' => ['seo', 'website'],
                'excerpt' => 'Optimasi sederhana yang bisa dilakukan sendiri setelah website live.',
                'content' => '<p>Isi meta title dan description unik per halaman. Gunakan heading H1 sekali saja. Optimalkan gambar (alt text + ukuran file). Daftarkan ke Google Search Console.</p><p>Publish artikel blog rutin — seperti yang Anda baca sekarang — untuk menarik traffic organik.</p>',
                'featured' => false,
                'views' => 421,
            ],
            [
                'slug' => 'keamanan-website-ssl-dan-backup',
                'title' => 'Keamanan Website: SSL, Backup, dan Update Rutin',
                'category' => 'teknologi',
                'tags' => ['keamanan', 'hosting'],
                'excerpt' => 'Tiga langkah wajib melindungi website bisnis dari downtime dan serangan.',
                'content' => '<p><strong>SSL</strong> mengenkripsi data pengunjung — wajib untuk checkout dan login. <strong>Backup</strong> mingguan minimal, ideal harian untuk e-commerce. <strong>Update</strong> CMS/plugin mencegah celah keamanan.</p><p>Semua paket hosting LaksanaTech include SSL gratis dan opsi backup terjadwal.</p>',
                'featured' => false,
                'views' => 156,
            ],
            [
                'slug' => 'mulai-toko-online-dari-nol',
                'title' => 'Mulai Toko Online dari Nol — Langkah Praktis',
                'category' => 'bisnis',
                'tags' => ['ecommerce', 'umkm', 'website'],
                'excerpt' => 'Dari pilih produk, domain, template, sampai payment gateway — alur lengkap untuk pemula.',
                'content' => '<p>1. Tentukan niche produk. 2. Daftarkan domain .shop atau .com. 3. Pilih template toko online. 4. Isi katalog & foto berkualitas. 5. Aktifkan payment gateway (Midtrans/Xendit). 6. Promosikan lewat sosial media.</p><p>LaksanaTech menyediakan checkout all-in-one: domain + template + hosting + bayar.</p>',
                'featured' => true,
                'views' => 633,
            ],
            [
                'slug' => 'tren-desain-website-2026',
                'title' => 'Tren Desain Website 2026 untuk Brand Indonesia',
                'category' => 'teknologi',
                'tags' => ['website', 'umkm'],
                'excerpt' => 'Minimalis, mobile-first, dan CTA jelas — tren yang dominan tahun ini.',
                'content' => '<p>Desain 2026 condong ke layout bersih, tipografi besar, warna brand konsisten, dan performa cepat di mobile. Animasi ringan dan dark mode opsional semakin populer.</p><p>Koleksi template LaksanaTech mengikuti tren ini — responsif, cepat, dan admin-friendly.</p>',
                'featured' => false,
                'views' => 298,
            ],
        ];

        foreach ($posts as $index => $data) {
            $post = BlogPost::query()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'blog_category_id' => $categoryIds[$data['category']],
                    'author_id' => $author->id,
                    'title' => $data['title'],
                    'excerpt' => $data['excerpt'],
                    'content' => $data['content'],
                    'status' => 'published',
                    'published_at' => now()->subDays(count($posts) - $index),
                    'views_count' => $data['views'],
                ],
            );

            $post->tags()->sync(array_map(fn ($t) => $tagIds[$t], $data['tags']));
        }
    }
}
