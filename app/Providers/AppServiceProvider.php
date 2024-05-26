<?php

namespace App\Providers;

use App\Services\LeaveService;
use App\Services\TeacherService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TeacherService::class, function ($app) {
            return new TeacherService();
        });

        $this->app->singleton(LeaveService::class,function ($app){
            return new LeaveService();
        });
    }

    public function boot(): void
    {
        //
    }
}
