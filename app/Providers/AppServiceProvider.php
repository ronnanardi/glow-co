<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        //
        {
            // Share cart count ke semua view
            View::composer('*', function ($view) {
                if (Auth::check() && Auth::user()->role === 'customer') {
                    $cart = Cart::with('items')
                                ->where('user_id', Auth::id())
                                ->first();

                    $cartCount = $cart ? $cart->items->sum('quantity') : 0;
                } else {
                    $cartCount = 0;
                }

                $view->with('cartCount', $cartCount);
            });
        }
    }
}
