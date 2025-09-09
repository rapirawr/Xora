<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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
        // Share cart item count with all views
        View::composer('*', function ($view) {
            $cartItemCount = 0;
            if (Auth::check()) {
                $user = Auth::user();
                $cart = $user->getOrCreateCart();
                $cartItemCount = $cart->items()->sum('quantity');
            }
            $view->with('cartItemCount', $cartItemCount);
        });
    }
}
