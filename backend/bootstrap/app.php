<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\Client\CheckLoginCandidate;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        using: function (){
            $adminRoutes = [
                'admin.php',
                'product.php',
                'user.php',
                'categories.php',
                'orders.php',
                'payment.php',
                'preview.php',
                'contact.php',
                'chart.php',
                'agent.php',
                'dashboard.php',
                'lucky_wheel.php',
                'missions.php',
                'questions.php',
                'rewards.php',
                'checkin.php',
            ];

            $clientRoutes = [
                // Add client routes here
            ];

            foreach ($adminRoutes as $route){
                // Đường dẫn login không yêu cầu xác thực, các đường dẫn khác yêu cầu xác thực
                if ($route === 'admin.php') {
                    Route::middleware('web')
                        ->prefix('admin')
                        ->name('admin.')
                        ->group(base_path("routes/admin/{$route}"));
                } else {
                    Route::middleware(['web', 'admin.auth'])
                        ->prefix('admin')
                        ->name('admin.')
                        ->group(base_path("routes/admin/{$route}"));
                }
            }

            foreach ($clientRoutes as $route){
                Route::middleware('web')
                     ->prefix('')
                    ->name('client.')
                     ->group(base_path("routes/client/{$route}"));
            }

            // API routes for Zalo Mini App authentication
            Route::middleware(['api', 'cors'])
                 ->prefix('')
                 ->group(base_path('routes/api.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware){
        $middleware->alias([
            'cors' => \App\Http\Middleware\Cors::class, // Đảm bảo middleware CORS được alias
            'admin.auth' => \App\Http\Middleware\AdminAuthentication::class, // Thêm middleware xác thực admin
        ]);
        //
        // $middleware->alias([
        //     'is_candidate' => CheckLoginCandidate::class
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions){
        //
    })->create();


