<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Service\UploadService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UploadService::class, function ($app) {
            return new UploadService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
