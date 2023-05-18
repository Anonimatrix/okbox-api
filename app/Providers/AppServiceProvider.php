<?php

namespace App\Providers;

use App\Billing\PaymentGateway;
use Illuminate\Support\ServiceProvider;
use App\Billing\PaymentGatewayContract;
use App\Models\Product;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
