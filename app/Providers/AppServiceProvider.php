<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
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
        Blade::if('route', function ($string) {
            return Str::startsWith(Route::currentRouteName(), $string);
        });

        // Blade::elseif('route2', function ($string) {
        //     return Str::startsWith(Route::currentRouteName(), $string);
        // });
    }
}
