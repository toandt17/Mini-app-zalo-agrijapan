<?php

use App\Http\Controllers\Admin\HomeController;
use Illuminate\Support\Facades\Route;

// Route::prefix('/')->name('admin.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');

// });

