<?php

namespace App\Providers;

use App\Services\Auth\RoleRegistryService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\Settings\SiteSettingsService::class);

        if ($publicPath = (config('app.public_path') ?: env('APP_PUBLIC_PATH'))) {
            $this->app->usePublicPath($publicPath);
        }
    }

    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            try {
                $request = request();

                // Lokal / LAN: URL mengikuti host request (mis. akses via IP HP).
                if ($this->app->environment('local') && $request->hasHeader('Host')) {
                    \Illuminate\Support\Facades\URL::forceRootUrl(
                        $request->getSchemeAndHttpHost().$request->getBaseUrl()
                    );
                }

                // Production: paksa HTTPS jika koneksi aman (SSL / reverse proxy).
                if ($this->app->environment('production') && $request->isSecure()) {
                    \Illuminate\Support\Facades\URL::forceScheme('https');
                }
            } catch (\Throwable) {
                // Request may be unavailable during early bootstrap.
            }
        }

        try {
            app(RoleRegistryService::class)->sync();
        } catch (\Throwable) {
            // Database may not be ready during install/migrate.
        }

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        \Illuminate\Support\Facades\View::composer('*', function ($view): void {
            $view->with('site', app(\App\Services\Settings\SiteSettingsService::class)->all());
        });

        \Illuminate\Support\Facades\View::composer('partials.navbar-services-menu', function ($view): void {
            $view->with('navServices', \App\Models\Service::query()
                ->with('category')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get());
        });
    }
}
