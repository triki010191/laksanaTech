<?php

use App\Livewire\Admin\Blog\ManagePosts;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\DomainExtensions\ManageDomainExtensions;
use App\Livewire\Admin\Employees\ManageEmployees;
use App\Livewire\Admin\Faqs\ManageFaqs;
use App\Livewire\Admin\HeroSlides\ManageHeroSlides;
use App\Livewire\Admin\Leads\ManageLeads;
use App\Livewire\Admin\Orders\ManageOrders;
use App\Livewire\Admin\Packages\ManagePackages;
use App\Livewire\Admin\Portfolios\ManagePortfolios;
use App\Livewire\Admin\Settings\ManageIntegrationsSettings;
use App\Livewire\Admin\Settings\ManagePaymentSettings;
use App\Livewire\Admin\Settings\ManageSiteSettings;
use App\Livewire\Admin\StaffProfile;
use App\Livewire\Admin\Templates\ManageTemplates;
use App\Livewire\Admin\Users\ManageUsers;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'role:super_admin|admin|sales|transaction_admin|freelancer'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', Dashboard::class)->name('dashboard');
        Route::get('/profile', StaffProfile::class)->name('profile');
        Route::get('/orders', ManageOrders::class)->name('orders.index');

        Route::middleware('role:super_admin|admin|sales')->group(function () {
            Route::get('/templates', ManageTemplates::class)->name('templates.index');
            Route::get('/packages', ManagePackages::class)->name('packages.index');
            Route::get('/domain-extensions', ManageDomainExtensions::class)->name('domain-extensions.index');
            Route::get('/hero-slides', ManageHeroSlides::class)->name('hero-slides.index');
            Route::get('/portfolios', ManagePortfolios::class)->name('portfolios.index');
            Route::get('/blog', ManagePosts::class)->name('blog.index');
            Route::get('/faqs', ManageFaqs::class)->name('faqs.index');
        });

        Route::middleware('role:super_admin|admin|sales|transaction_admin')->group(function () {
            Route::get('/leads', ManageLeads::class)->name('leads.index');
        });

        Route::middleware('role:super_admin|admin')->group(function () {
            Route::get('/settings', ManageSiteSettings::class)->name('settings.index');
            Route::get('/employees', ManageEmployees::class)->name('employees.index');
        });

        Route::middleware('role:super_admin')->group(function () {
            Route::get('/payment-settings', ManagePaymentSettings::class)->name('payment-settings.index');
            Route::get('/integrations', ManageIntegrationsSettings::class)->name('integrations.index');
            Route::get('/users', ManageUsers::class)->name('users.index');
        });
    });
