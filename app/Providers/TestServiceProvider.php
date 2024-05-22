<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
        $this->app->bind('App\Service\TestServiceInterface','App\Service\TestService');
    }

    public function boot(): void
    {
        //
    }
}
