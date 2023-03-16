<?php

namespace App\Providers;

use App\Services\GoogleServices\GoogleSearchConsoleService;
use Illuminate\Support\ServiceProvider;

class GoogleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('GoogleSearchConsoleService', function ($app) {
            return new GoogleSearchConsoleService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
