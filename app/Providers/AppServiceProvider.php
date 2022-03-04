<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
        
        View::share('currency', config('store.currency', [
            'symbol' => '$',
            'code' => 'USD',
        ]));
        
        View::share('shipping_methods', config('store.shipping_methods', [
            'standard' => [
                'name' => 'Standard Shipping',
                'price' => '10.00'
            ]
        ]));
    }
}
