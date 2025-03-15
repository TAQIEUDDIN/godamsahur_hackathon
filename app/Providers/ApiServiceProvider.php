<?php

namespace App\Providers;

use App\Services\FoursquareService;
use App\Services\MapboxService;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(MapboxService::class, function ($app) {
            return new MapboxService();
        });

        $this->app->singleton(FoursquareService::class, function ($app) {
            return new FoursquareService();
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
