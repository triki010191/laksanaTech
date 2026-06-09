<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\DomainExtension;
use App\Models\Faq;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\WebsitePackage;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use App\Services\Home\HomePageService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly ServiceRepositoryInterface $services,
        private readonly HomePageService $homePage,
    ) {}

    public function index(): View
    {
        return view('pages.home', [
            'featuredTemplates' => $this->homePage->featuredTemplates(3),
            'services' => Service::query()
                ->where('is_active', true)
                ->whereIn('slug', collect(config('laksana.layanan_menu.bidang.items', []))->pluck('slug'))
                ->orderBy('sort_order')
                ->get(),
            'domainOffers' => $this->homePage->domainOffers(),
            'featuredPortfolios' => Portfolio::query()
                ->with('images')
                ->where('is_published', true)
                ->where('is_featured', true)
                ->orderBy('sort_order')
                ->limit(6)
                ->get(),
            'pageTitle' => config('laksana.seo.default_title'),
            'pageDescription' => config('laksana.seo.default_description'),
        ]);
    }

    public function about(): View
    {
        return view('pages.about', [
            'pageTitle' => 'Tentang Kami — '.config('laksana.brand.name'),
        ]);
    }

    public function services(): View
    {
        $slugs = collect(config('laksana.layanan_menu.bidang.items', []))->pluck('slug');

        return view('pages.services', [
            'services' => Service::query()
                ->where('is_active', true)
                ->whereIn('slug', $slugs)
                ->get()
                ->sortBy(fn (Service $service) => $slugs->search($service->slug))
                ->values(),
            'pageTitle' => 'Layanan — '.config('laksana.brand.name'),
        ]);
    }

    public function serviceShow(string $slug): View
    {
        $service = $this->services->findBySlug($slug);

        abort_if(! $service, 404);

        return view('pages.services.show', [
            'service' => $service,
            'pageTitle' => $service->name.' — '.config('laksana.brand.name'),
            'pageDescription' => $service->short_description,
        ]);
    }

    public function packages(): View
    {
        return view('pages.packages', [
            'domainOffers' => $this->homePage->domainOffers(),
            'extensions' => DomainExtension::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'packages' => WebsitePackage::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'pageTitle' => 'Paket Domain & Hosting — '.config('laksana.brand.name'),
            'pageDescription' => 'Harga domain, paket hosting, dan website profesional tahunan dari LaksanaTech.',
        ]);
    }

    public function portfolio(): View
    {
        return view('pages.portfolio', [
            'portfolios' => Portfolio::query()
                ->with(['category', 'images'])
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->paginate(12),
            'pageTitle' => 'Portofolio — '.config('laksana.brand.name'),
        ]);
    }

    public function blog(): View
    {
        $categorySlug = request('kategori');

        $query = BlogPost::query()
            ->with(['category', 'author'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->latest('published_at');

        if ($categorySlug) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        return view('pages.blog', [
            'posts' => $query->paginate(9)->withQueryString(),
            'categories' => BlogCategory::query()->orderBy('name')->get(),
            'activeCategory' => $categorySlug,
            'pageTitle' => 'Blog — '.config('laksana.brand.name'),
            'pageDescription' => 'Artikel dan insight seputar website, domain, hosting, dan digitalisasi bisnis.',
        ]);
    }

    public function blogShow(string $slug): View
    {
        $post = BlogPost::query()
            ->with(['category', 'author', 'tags'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        $post->increment('views_count');

        return view('pages.blog.show', [
            'post' => $post,
            'related' => BlogPost::query()
                ->with('category')
                ->where('status', 'published')
                ->where('published_at', '<=', now())
                ->where('id', '!=', $post->id)
                ->when($post->blog_category_id, fn ($q) => $q->where('blog_category_id', $post->blog_category_id))
                ->latest('published_at')
                ->limit(3)
                ->get(),
            'pageTitle' => $post->title.' — '.config('laksana.brand.name'),
            'pageDescription' => $post->excerpt,
        ]);
    }

    public function faq(): View
    {
        return view('pages.faq', [
            'faqs' => Faq::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'pageTitle' => 'Pertanyaan — '.config('laksana.brand.name'),
        ]);
    }

    public function tutorial(): View
    {
        return view('pages.tutorial', [
            'pageTitle' => 'Tutorial Pesan Website — '.config('laksana.brand.name'),
            'pageDescription' => 'Panduan lengkap cara pesan website di LaksanaTech: mulai dari domain atau template, 7 langkah mudah hingga website siap 2×24 jam.',
        ]);
    }

    public function contact(): View
    {
        return view('pages.contact', [
            'pageTitle' => 'Kontak — '.config('laksana.brand.name'),
        ]);
    }
}
