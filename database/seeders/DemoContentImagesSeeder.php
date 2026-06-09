<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Portfolio;
use App\Models\PortfolioImage;
use App\Models\Service;
use App\Models\Template;
use Database\Seeders\Support\DemoImageDownloader;
use Illuminate\Database\Seeder;

class DemoContentImagesSeeder extends Seeder
{
    public function run(): void
    {
        $downloader = new DemoImageDownloader;

        $this->seedTemplateImages($downloader);
        $this->seedServiceImages($downloader);
        $this->seedPortfolioImages($downloader);
        $this->seedBlogImages($downloader);
    }

    private function seedTemplateImages(DemoImageDownloader $downloader): void
    {
        $images = [
            'umkm-modern' => 'photo-1556742049-0cfed4f6a45d',
            'umkm-premium-storefront' => 'photo-1441986300917-64674bd600d8',
            'warung-digital-menu' => 'photo-1555396273-367ea4eb4db5',
            'jasa-lokal-booking' => 'photo-1560066984-138d9834c685',
            'company-profile-pro' => 'photo-1497366216548-37526070297c',
            'corporate-blue' => 'photo-1486406146926-c627a92ad1ab',
            'konsultan-profesional' => 'photo-1454165804606-c3d57bc86b40',
            'startup-launch-page' => 'photo-1522071820081-009f0129c71c',
            'toko-online-lite' => 'photo-1556740758-90de374c12ad',
            'fashion-boutique-store' => 'photo-1445205170230-053b83016050',
            'digital-product-store' => 'photo-1516321318423-f06f85e504b3',
            'marketplace-multi-seller' => 'photo-1557821552-171051766423',
            'sekolah-digital' => 'photo-1523050854058-8df84110bf9c',
            'madrasah-modern' => 'photo-1503676260728-1c00da094a0b',
            'kampus-pmb-online' => 'photo-1541339907195-e08756adf5f3',
            'bimbel-kursus-online' => 'photo-1434030216411-0b793f4b4173',
        ];

        foreach ($images as $slug => $photoId) {
            $template = Template::query()->where('slug', $slug)->first();
            if (! $template || $template->preview_image) {
                continue;
            }

            $path = "templates/{$slug}.jpg";
            $stored = $downloader->downloadOrFallback(
                $downloader->unsplash($photoId, 960),
                $path,
                "template-{$slug}"
            );

            if ($stored) {
                $template->update(['preview_image' => $stored]);
            }
        }
    }

    private function seedServiceImages(DemoImageDownloader $downloader): void
    {
        $images = [
            'keamanan-dan-akses' => 'photo-1558618666-fcd25c85cd64',
            'infrastruktur-jaringan' => 'photo-1558494949-ef010cbdcc31',
            'pengadaan-it-instansi' => 'photo-1581091226033-db79e960caa4',
            'pesanan-khusus' => 'photo-1461743480684-dccba630e2f6',
        ];

        foreach ($images as $slug => $photoId) {
            $service = Service::query()->where('slug', $slug)->first();
            if (! $service || $service->preview_image) {
                continue;
            }

            $path = "services/{$slug}.jpg";
            $stored = $downloader->downloadOrFallback(
                $downloader->unsplash($photoId, 960),
                $path,
                "service-{$slug}"
            );

            if ($stored) {
                $service->update(['preview_image' => $stored]);
            }
        }
    }

    private function seedPortfolioImages(DemoImageDownloader $downloader): void
    {
        $categoryPhotos = [
            'website' => [
                'photo-1467232001-7d5680ccc6b0',
                'photo-1498050108023-c5249f4df085',
                'photo-1547658719-da2b51169166',
            ],
            'aplikasi-sistem' => [
                'photo-1551288049-bebda4e38f71',
                'photo-1555949963-aa79dcee981c',
                'photo-1517694712202-14dd9538aa97',
            ],
            'pos-inventory' => [
                'photo-1576091160399-112ba8d25d1d',
                'photo-1587854692152-cf2652cfba29',
                'photo-1631815588090-d4bfec5b8cc0',
            ],
            'instalasi' => [
                'photo-1558618666-fcd25c85cd64',
                'photo-1563986768609-322da13575f3',
            ],
        ];

        $categoryIndex = [];

        Portfolio::query()
            ->with('category')
            ->orderBy('sort_order')
            ->each(function (Portfolio $portfolio) use ($downloader, $categoryPhotos, &$categoryIndex): void {
                $categorySlug = $portfolio->category?->slug ?? 'website';
                $photos = $categoryPhotos[$categorySlug] ?? $categoryPhotos['website'];
                $index = $categoryIndex[$categorySlug] ?? 0;
                $photoId = $photos[$index % count($photos)];
                $categoryIndex[$categorySlug] = $index + 1;

                $path = "portfolios/{$portfolio->slug}.jpg";

                if ($portfolio->images()->exists()) {
                    return;
                }

                $stored = $downloader->downloadOrFallback(
                    $downloader->unsplash($photoId, 960),
                    $path,
                    "portfolio-{$portfolio->slug}"
                );

                if (! $stored) {
                    return;
                }

                PortfolioImage::query()->updateOrCreate(
                    ['portfolio_id' => $portfolio->id, 'sort_order' => 1],
                    ['path' => $stored, 'caption' => $portfolio->title],
                );
            });
    }

    private function seedBlogImages(DemoImageDownloader $downloader): void
    {
        $images = [
            'cara-memilih-domain-untuk-bisnis' => 'photo-1450101499163-c8848c66ca85',
            '5-alasan-umkm-butuh-website-profesional' => 'photo-1556761175-b413da4baf72',
            'perbedaan-hosting-shared-dan-vps' => 'photo-1558494949-ef010cbdcc31',
            'checklist-website-sekolah-ppdb-online' => 'photo-1509062522256-531375050396',
            'tips-seo-dasar-website-baru' => 'photo-1432888622747-4eb9a8f2c293',
            'keamanan-website-ssl-dan-backup' => 'photo-1563986768494-4cdf4f577592',
            'mulai-toko-online-dari-nol' => 'photo-1472851294607-062e0d8eafbb',
            'tren-desain-website-2026' => 'photo-1547658719-da2b51169166',
        ];

        foreach ($images as $slug => $photoId) {
            $post = BlogPost::query()->where('slug', $slug)->first();
            if (! $post || $post->featured_image) {
                continue;
            }

            $path = "blog/{$slug}.jpg";
            $stored = $downloader->downloadOrFallback(
                $downloader->unsplash($photoId, 960),
                $path,
                "blog-{$slug}"
            );

            if ($stored) {
                $post->update(['featured_image' => $stored]);
            }
        }
    }
}
