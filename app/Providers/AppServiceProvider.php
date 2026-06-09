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
    }

    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            try {
                $request = request();

                if ($request->hasHeader('Host')) {
                    \Illuminate\Support\Facades\URL::forceRootUrl(
                        $request->getSchemeAndHttpHost().$request->getBaseUrl()
                    );
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
    }
}
