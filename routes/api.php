<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Blog\BlogPostController;
use App\Http\Controllers\Api\V1\Catalog\FaqController;
use App\Http\Controllers\Api\V1\Catalog\ServiceController;
use App\Http\Controllers\Api\V1\Catalog\TemplateController;
use App\Http\Controllers\Api\V1\Customer\InvoiceController;
use App\Http\Controllers\Api\V1\Customer\OrderController;
use App\Http\Controllers\Api\V1\Customer\ProjectController;
use App\Http\Controllers\Api\V1\Customer\SupportTicketController;
use App\Http\Controllers\Api\V1\Payment\PaymentWebhookController;
use App\Http\Controllers\Api\V1\Portfolio\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('health', fn () => response()->json(['status' => 'ok', 'service' => 'LaksanaTech API v1']));

    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    });

    Route::get('services', [ServiceController::class, 'index']);
    Route::get('services/{slug}', [ServiceController::class, 'show']);

    Route::get('templates', [TemplateController::class, 'index']);
    Route::get('templates/{slug}', [TemplateController::class, 'show']);

    Route::get('portfolios', [PortfolioController::class, 'index']);
    Route::get('portfolios/{slug}', [PortfolioController::class, 'show']);

    Route::get('blog', [BlogPostController::class, 'index']);
    Route::get('blog/{slug}', [BlogPostController::class, 'show']);

    Route::get('faqs', [FaqController::class, 'index']);

    Route::post('payments/webhook/{gateway}', [PaymentWebhookController::class, 'handle'])
        ->name('api.payments.webhook');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);

        Route::get('customer/orders', [OrderController::class, 'index']);
        Route::get('customer/orders/{order}', [OrderController::class, 'show']);
        Route::get('customer/invoices', [InvoiceController::class, 'index']);
        Route::get('customer/projects', [ProjectController::class, 'index']);
        Route::get('customer/support-tickets', [SupportTicketController::class, 'index']);
        Route::post('customer/support-tickets', [SupportTicketController::class, 'store']);
    });
});
