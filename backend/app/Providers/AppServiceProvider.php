<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\User\UserInterface;
use App\Repositories\Category\CategoryInterface;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Order\OrderInterface;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Payment\PaymentInterface;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\Product\ProductInterface;
use App\Repositories\Product\ProductRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Location\LocationInterface;
use App\Repositories\Location\LocationRepository;
use App\Repositories\Agent\AgentInterface;
use App\Repositories\Agent\AgentRepository;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(CategoryInterface::class, CategoryRepository::class);
        $this->app->bind(OrderInterface::class, OrderRepository::class);
        $this->app->bind(PaymentInterface::class, PaymentRepository::class);
        $this->app->bind(ProductInterface::class, ProductRepository::class);
        $this->app->bind(LocationInterface::class, LocationRepository::class);
        $this->app->bind(AgentInterface::class, AgentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
