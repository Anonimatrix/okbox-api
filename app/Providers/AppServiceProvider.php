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
        $this->app->singleton(PaymentGatewayContract::class, function ($app) {
            $type = $app->make('router')->input('type');
            if ($type === 'gateway') {
                return new PaymentGateway('usd');
            } else {
                return new PaymentGateway('eur');
            }
        });
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
