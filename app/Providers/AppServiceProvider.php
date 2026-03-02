<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register các dịch vụ ứng dụng.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap các dịch vụ ứng dụng.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
