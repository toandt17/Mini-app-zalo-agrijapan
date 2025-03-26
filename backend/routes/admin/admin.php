<?php

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;

// Các route đăng nhập - không yêu cầu xác thực
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Các route yêu cầu đăng nhập
Route::middleware('admin.auth')->group(function() {
    Route::get('/', [HomeController::class, 'index'])->name('index');
});

