<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Custom Sanctum Token Retrieval from Cookie
        Sanctum::getAccessTokenFromRequestUsing(function ($request) {
            // Retrieve token from the HTTPOnly cookie named 'api_token'
            return $request->cookie('api_token');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
