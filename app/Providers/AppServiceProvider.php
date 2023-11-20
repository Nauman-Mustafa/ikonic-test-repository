<?php

namespace App\Providers;

use App\Services\MerchantService;
use App\Interfaces\MerchantServiceInterface;
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
        $this->app->singleton(MerchantService::class, function ($app) {
        return new MerchantService(/* any dependencies you need */);
    });
    
        $this->app->bind(MerchantServiceInterface::class, MerchantService::class);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
