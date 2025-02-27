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
            ];

            $clientRoutes = [

            ];


            foreach ($adminRoutes as $route){
                Route::middleware('web')
                     ->prefix('admin')
                     ->name('admin.')
                     ->group(base_path("routes/admin/{$route}"));
            }

            foreach ($clientRoutes as $route){
                Route::middleware('web')
                     ->prefix('')
                    ->name('client.')
                     ->group(base_path("routes/client/{$route}"));
            }
        },
    )
    ->withMiddleware(function (Middleware $middleware){
        //
        // $middleware->alias([
        //     'is_candidate' => CheckLoginCandidate::class
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions){
        //
    })->create();

