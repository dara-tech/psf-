<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Schema::defaultStringLength(191);
        
        // Handle URL scheme based on actual request, not just APP_URL
        // This prevents issues when APP_URL is HTTPS but accessing via HTTP
        if (config('app.env') !== 'local') {
            // Only force HTTPS if the request is actually secure
            // This allows HTTP access when SSL is not configured or when accessing via HTTP
            if (request()->secure()) {
                \Illuminate\Support\Facades\URL::forceScheme('https');
            } else {
                // If accessing via HTTP, ensure URLs are generated as HTTP
                // This prevents redirect loops when APP_URL is HTTPS but accessing via HTTP
                \Illuminate\Support\Facades\URL::forceScheme('http');
            }
        }

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        

    }
}
