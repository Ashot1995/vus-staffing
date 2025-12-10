<?php

namespace App\Providers;

use App\Services\DatabaseTranslationLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\FileLoader;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('translation.loader', function ($app) {
            $fileLoader = new FileLoader($app['files'], $app['path.lang']);

            return new DatabaseTranslationLoader($fileLoader);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
