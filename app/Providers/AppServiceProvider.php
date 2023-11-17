<?php

namespace App\Providers;

use App\Contracts\AuthInterface;
use App\Http\Controllers\AuthController;
use Illuminate\Support\ServiceProvider;
use URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            AuthInterface::class,
            AuthController::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme("https");
        }
    }
}
