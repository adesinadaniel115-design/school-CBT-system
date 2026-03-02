<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        // If the application URL is configured to use https, or the
        // FORCE_HTTPS env flag is set, instruct Laravel to generate
        // secure URLs and cookies.
        if (Str::startsWith(config('app.url'), 'https') || env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }
    }
}
