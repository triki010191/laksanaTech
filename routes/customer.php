<?php

use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\InvoicePaymentController;
use App\Livewire\Customer\CreateTicket;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:customer'])
    ->prefix('dashboard')
    ->name('customer.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders', [DashboardController::class, 'orders'])->name('orders');
        Route::get('/invoices', [DashboardController::class, 'invoices'])->name('invoices');
        Route::get('/invoices/{invoice}/pay', [InvoicePaymentController::class, 'pay'])->name('invoices.pay');
        Route::get('/projects', [DashboardController::class, 'projects'])->name('projects');
        Route::get('/tickets', [DashboardController::class, 'tickets'])->name('tickets');
        Route::get('/tickets/create', CreateTicket::class)->name('tickets.create');
    });

Route::middleware('auth')->group(function () {
    Route::get('/payment/finish', [InvoicePaymentController::class, 'finish'])->name('payment.finish');
});
