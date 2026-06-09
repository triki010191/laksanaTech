<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Template;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $urls = collect([
            ['loc' => route('home'), 'priority' => '1.0'],
            ['loc' => route('about'), 'priority' => '0.8'],
            ['loc' => route('services'), 'priority' => '0.9'],
            ['loc' => route('portfolio'), 'priority' => '0.9'],
            ['loc' => route('tutorial'), 'priority' => '0.9'],
            ['loc' => route('blog'), 'priority' => '0.8'],
            ['loc' => route('pertanyaan'), 'priority' => '0.7'],
            ['loc' => route('contact'), 'priority' => '0.8'],
        ]);

        Service::query()->where('is_active', true)->pluck('slug')->each(
            fn ($slug) => $urls->push(['loc' => url('/layanan/'.$slug), 'priority' => '0.7'])
        );

        Portfolio::query()->where('is_published', true)->pluck('slug')->each(
            fn ($slug) => $urls->push(['loc' => url('/portofolio/'.$slug), 'priority' => '0.7'])
        );

        BlogPost::query()->where('status', 'published')->pluck('slug')->each(
            fn ($slug) => $urls->push(['loc' => url('/blog/'.$slug), 'priority' => '0.6'])
        );

        Template::query()->where('is_active', true)->pluck('slug')->each(
            fn ($slug) => $urls->push(['loc' => url('/template/'.$slug), 'priority' => '0.6'])
        );

        $content = view('seo.sitemap', ['urls' => $urls])->render();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        $content = "User-agent: *\nAllow: /\nSitemap: ".route('sitemap')."\n";

        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
