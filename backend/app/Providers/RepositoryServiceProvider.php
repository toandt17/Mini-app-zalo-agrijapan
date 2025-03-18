<?php

namespace App\Providers;

use App\Repositories\Interfaces\SpinWheelRepositoryInterface;
use App\Repositories\SpinWheelRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            SpinWheelRepositoryInterface::class,
            SpinWheelRepository::class
        );

        // Đăng ký thêm các repository khác trong tương lai
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
