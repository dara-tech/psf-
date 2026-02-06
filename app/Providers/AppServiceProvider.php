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
            // Detect if request is secure (handles both direct HTTPS and proxy scenarios)
            $isSecure = request()->secure() || 
                       request()->server('HTTP_X_FORWARDED_PROTO') === 'https' ||
                       request()->server('HTTPS') === 'on' ||
                       (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] === 'https');
            
            // Set the root URL first with correct scheme
            $host = request()->getHttpHost();
            if ($isSecure) {
                \Illuminate\Support\Facades\URL::forceRootUrl('https://' . $host);
                \Illuminate\Support\Facades\URL::forceScheme('https');
            } else {
                \Illuminate\Support\Facades\URL::forceRootUrl('http://' . $host);
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
