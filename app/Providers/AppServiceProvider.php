<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\GioHang;

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

        View::composer('*', function ($view) {
            $cartCount = 0;
            if (Auth::check()) {
                $cartCount = GioHang::where('nguoi_dung_id', Auth::id())->count();
            } else {
                $cart = session()->get('cart', []);
                $cartCount = count($cart);
            }
            $view->with('cartCount', $cartCount);
        });
    }
}
