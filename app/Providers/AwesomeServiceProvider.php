<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AwesomeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('App\Service\AwesomeServiceInterface','App\Service\AwesomeService');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
