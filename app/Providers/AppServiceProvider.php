<?php

namespace App\Providers;

use App\Contracts\CartServiceInterface;
use App\Contracts\CheckOutServiceInterface;
use App\Contracts\OrderServiceInterface;
use App\Contracts\UserServiceInterface;
use App\Services\CartService;
use App\Services\CheckOutService;
use App\Services\OrderService;
use App\Services\UserService;
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
        $this->app->bind(CartServiceInterface::class, CartService::class);
        $this->app->bind(CheckOutServiceInterface::class, CheckOutService::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
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
