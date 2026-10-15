<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
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
        // Force HTTPS di environment production atau jika APP_URL menggunakan https
        if (config('app.env') === 'production' || str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        // Super-admin bypasses all permission checks
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });
    }
}
