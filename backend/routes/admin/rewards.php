<?php

use App\Http\Controllers\Admin\Reward\RewardController;
use Illuminate\Support\Facades\Route;


    // Quản lý quà tặng
    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::get('/rewards/create', [RewardController::class, 'create'])->name('rewards.create');
    Route::post('/rewards', [RewardController::class, 'store'])->name('rewards.store');
    Route::get('/rewards/{id}', [RewardController::class, 'show'])->name('rewards.show');
    Route::get('/rewards/{id}/edit', [RewardController::class, 'edit'])->name('rewards.edit');
    Route::put('/rewards/{id}', [RewardController::class, 'update'])->name('rewards.update');
    Route::delete('/rewards/{id}', [RewardController::class, 'destroy'])->name('rewards.destroy');
    Route::get('/rewards/search', [RewardController::class, 'search'])->name('rewards.search');
