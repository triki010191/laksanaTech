<?php

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ScreenshotController;
use App\Http\Controllers\Web\SeoController;
use App\Http\Controllers\Web\TemplateController;
use App\Livewire\Checkout\WebsiteCheckout;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', [HomeController::class, 'about'])->name('about');
Route::get('/layanan', [HomeController::class, 'services'])->name('services');
Route::get('/layanan/{slug}', [HomeController::class, 'serviceShow'])->name('services.show');
Route::get('/paket-domain', [HomeController::class, 'packages'])->name('packages');
Route::get('/portofolio', [HomeController::class, 'portfolio'])->name('portfolio');
Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [HomeController::class, 'blogShow'])->name('blog.show');
Route::get('/tutorial', [HomeController::class, 'tutorial'])->name('tutorial');
Route::get('/pertanyaan', [HomeController::class, 'faq'])->name('pertanyaan');
Route::redirect('/faq', '/pertanyaan');
Route::get('/kontak', [HomeController::class, 'contact'])->name('contact');

Route::get('/cari-design', [TemplateController::class, 'index'])->name('designs');
Route::get('/screenshot', ScreenshotController::class)->name('screenshot');
Route::redirect('/template', '/cari-design');
Route::get('/template/{slug}', [TemplateController::class, 'show'])->name('templates.show');
Route::get('/pesan-website', WebsiteCheckout::class)->name('checkout');
Route::get('/pembayaran/{invoice}', \App\Livewire\Checkout\CheckoutPayment::class)->name('checkout.payment');

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
