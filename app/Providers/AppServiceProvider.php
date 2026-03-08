<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ensure correct base URL in production when APP_URL may be misconfigured
        if (config('app.env') === 'production' && $this->app->runningInConsole() === false) {
            $configUrl = config('app.url', '');
            if (str_contains($configUrl, 'localhost') || str_contains($configUrl, '127.0.0.1')) {
                URL::forceRootUrl('https://vus-bemanning.se');
            }
        }
    }
}
