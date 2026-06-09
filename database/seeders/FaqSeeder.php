<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $brand = config('laksana.brand.name', 'laksanatech');
        $company = config('laksana.brand.legal_name', 'CV. Laksana Solution');
        $wa = config('laksana.brand.whatsapp', '6285212455505');
        $address = config('laksana.brand.address');

        $items = [
            [
                'sort_order' => 1,
                'category' => 'Umum',
                'question' => "Apa itu {$brand}?",
                'answer' => "<strong>{$brand}</strong> adalah platform digital dari {$company} yang dirancang untuk membantu UMKM, bisnis skala kecil, sekolah, dan instansi memiliki website profesional dengan mudah dan terjangkau. Website Anda akan siap digunakan dalam waktu maksimal <strong>2×24 jam</strong> setelah pemesanan, lengkap dengan domain, optimasi SEO, dan email bisnis untuk mendukung pertumbuhan usaha Anda.",
            ],
            [
                'sort_order' => 2,
                'category' => 'Pemesanan',
                'question' => "Bagaimana cara melakukan pembelian website di {$brand}?",
                'answer' => 'Proses pembelian sangat mudah. Silakan pilih domain atau template website terlebih dahulu melalui menu <a href="'.route('designs').'" class="text-brand-primary hover:underline">Cari Design</a> atau <a href="'.route('packages').'" class="text-brand-primary hover:underline">Paket Domain</a>, kemudian ikuti langkah-langkah checkout yang tersedia. Anda juga dapat memasukkan kode promo untuk mendapatkan potongan harga. Panduan lengkap dapat dilihat pada halaman <a href="'.route('tutorial').'" class="text-brand-primary hover:underline">Tutorial</a> kami.',
            ],
            [
                'sort_order' => 3,
                'category' => 'Pembayaran',
                'question' => "Metode pembayaran apa saja yang tersedia di {$brand}?",
                'answer' => "{$brand} menyediakan berbagai metode pembayaran melalui gateway terintegrasi (Midtrans/Xendit). Tersedia Virtual Account (BCA, Mandiri, BRI, BNI, Permata), e-wallet (OVO, DANA, LinkAja), QRIS, kartu kredit, serta opsi cicilan Paylater. Harap selesaikan pembayaran dalam waktu <strong>2×24 jam</strong> sejak pesanan dibuat untuk menghindari pembatalan otomatis.",
            ],
            [
                'sort_order' => 4,
                'category' => 'Pemesanan',
                'question' => 'Apa yang terjadi setelah saya melakukan pembayaran?',
                'answer' => 'Setelah pembayaran berhasil, Anda akan menerima email konfirmasi invoice. Tim kami akan segera memproses pembuatan website dengan estimasi waktu maksimal <strong>2×24 jam</strong>. Setelah website siap, kami mengirimkan email berisi akses website beserta panduan pengeditannya. Jika memerlukan bantuan, hubungi Customer Service kami via <a href="https://wa.me/'.$wa.'" target="_blank" rel="noopener" class="text-brand-primary hover:underline">WhatsApp</a>.',
            ],
            [
                'sort_order' => 5,
                'category' => 'Email',
                'question' => 'Apa itu Email Bisnis dan bagaimana cara mendapatkannya?',
                'answer' => 'Email Bisnis adalah alamat email profesional menggunakan nama domain Anda — misalnya <em>nama@domainanda.com</em>. Email ini meningkatkan kredibilitas usaha saat berkomunikasi dengan klien dan mitra bisnis. Untuk mengaktifkan Email Bisnis, hubungi tim Customer Service kami via <a href="https://wa.me/'.$wa.'" target="_blank" rel="noopener" class="text-brand-primary hover:underline">WhatsApp</a> setelah website Anda siap digunakan.',
            ],
            [
                'sort_order' => 6,
                'category' => 'Template',
                'question' => "Apa itu Template Website {$brand}?",
                'answer' => 'Template Website adalah desain website siap pakai yang telah dirancang tim '.$company.'. Kami menyediakan koleksi template untuk UMKM, company profile, toko online, dan sekolah. Anda cukup mengganti logo, teks, dan gambar sesuai kebutuhan tanpa desain dari nol. Ingin mengganti template di kemudian hari? Layanan ini tersedia <strong>gratis</strong> — hubungi Customer Service via <a href="https://wa.me/'.$wa.'" target="_blank" rel="noopener" class="text-brand-primary hover:underline">WhatsApp</a>.',
            ],
            [
                'sort_order' => 7,
                'category' => 'Perpanjangan',
                'question' => "Bagaimana cara melakukan perpanjangan website di {$brand}?",
                'answer' => 'Anda akan menerima notifikasi melalui email dan WhatsApp sebelum masa berlangganan domain dan hosting berakhir. Kami menyarankan perpanjangan sebelum masa aktif habis agar data website tetap aman dan website tetap dapat diakses pengunjung. Untuk bantuan perpanjangan, hubungi tim kami via WhatsApp atau email.',
            ],
            [
                'sort_order' => 8,
                'category' => 'Domain',
                'question' => "Apakah saya bisa mengganti domain website di {$brand}?",
                'answer' => 'Ya, penggantian domain dapat dilakukan. Layanan ini dikenakan biaya tambahan karena memerlukan pendaftaran ulang domain pada sistem kami. Pastikan nama domain baru sudah Anda tentukan sebelum memproses penggantian. Hubungi Customer Service via <a href="https://wa.me/'.$wa.'" target="_blank" rel="noopener" class="text-brand-primary hover:underline">WhatsApp</a> untuk informasi biaya dan prosesnya.',
            ],
            [
                'sort_order' => 9,
                'category' => 'Kebijakan',
                'question' => "Apakah pembelian website di {$brand} dapat dibatalkan?",
                'answer' => 'Mohon maaf, '.$brand.' tidak menyediakan pengembalian dana (refund) karena setiap domain dan website yang dipesan langsung terdaftar pada sistem domain dan server kami. Pastikan nama domain serta seluruh data pesanan sudah benar sebelum menyelesaikan checkout.',
            ],
            [
                'sort_order' => 10,
                'category' => 'E-Commerce',
                'question' => "Apakah {$brand} bisa membuat website toko online / e-commerce?",
                'answer' => 'Ya. Kami menyediakan template toko online profesional dan responsif, dilengkapi katalog produk, integrasi pembayaran (transfer bank, e-wallet, QRIS), manajemen produk, dan optimasi SEO. Lihat koleksi template di halaman <a href="'.route('designs').'" class="text-brand-primary hover:underline">Cari Design</a> atau konsultasi kebutuhan e-commerce custom via <a href="'.route('contact').'" class="text-brand-primary hover:underline">Kontak</a>.',
            ],
            [
                'sort_order' => 11,
                'category' => 'Pesanan Khusus',
                'question' => "Apakah {$brand} menerima pesanan khusus seperti website custom, sistem ERP, atau aplikasi?",
                'answer' => 'Ya. Selain template website, '.$company.' juga memiliki tim yang siap membangun solusi digital sesuai kebutuhan bisnis Anda — website custom, integrasi AI, sistem ERP, aplikasi mobile, POS, dan integrasi pihak ketiga. Kunjungi halaman <a href="'.route('services.show', 'pesanan-khusus').'" class="text-brand-primary hover:underline">Pesanan Khusus</a> atau hubungi kami via <a href="https://wa.me/'.$wa.'" target="_blank" rel="noopener" class="text-brand-primary hover:underline">WhatsApp</a> untuk konsultasi.',
            ],
            [
                'sort_order' => 12,
                'category' => 'Kontak',
                'question' => "Di mana alamat kantor {$brand}?",
                'answer' => 'Kantor kami berlokasi di <strong>'.$address.'</strong>. Anda juga dapat menghubungi kami melalui <a href="https://wa.me/'.$wa.'" target="_blank" rel="noopener" class="text-brand-primary hover:underline">WhatsApp</a> ('.config('laksana.brand.phone').') atau email <a href="mailto:'.config('laksana.brand.email').'" class="text-brand-primary hover:underline">'.config('laksana.brand.email').'</a> untuk pertanyaan lebih lanjut.',
            ],
        ];

        $activeOrders = [];

        foreach ($items as $item) {
            Faq::query()->updateOrCreate(
                ['sort_order' => $item['sort_order']],
                array_merge($item, ['is_active' => true]),
            );
            $activeOrders[] = $item['sort_order'];
        }

        Faq::query()
            ->whereNotIn('sort_order', $activeOrders)
            ->update(['is_active' => false]);
    }
}
