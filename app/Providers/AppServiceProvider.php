<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Providers\PlainTextUserProvider;

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
        // Register custom user provider for plain text password authentication
        Auth::provider('custom', function ($app, array $config) {
            return new PlainTextUserProvider();
        });
    }
}
