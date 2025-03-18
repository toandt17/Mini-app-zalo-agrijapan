<?php

use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\GameController as ApiGameController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Game\LuckyWheelController;

Route::prefix('/game')->name('game.')->group(function () {
    Route::get('/', [GameController::class, 'index_lucky'])->name('index_lucky');
    Route::get('/them-moi', [GameController::class, 'add_lucky'])->name('add_lucky');
    Route::post('/them-moi', [GameController::class, 'store_lucky'])->name('store_lucky');
    Route::get('/chinh-sua/{id}', [GameController::class, 'edit_lucky'])->name('edit_lucky');
    Route::put('/update/{id}', [GameController::class, 'update_lucky'])->name('update_lucky');
    // Route::delete('/xoa/{id}', [CategoryController::class, 'delete'])->name('delete');
});

// Routes quản lý vòng quay may mắn
Route::group(['prefix' => 'lucky-wheel', 'as' => 'lucky_wheel.'], function () {
    Route::get('/', [LuckyWheelController::class, 'index'])->name('index');
    Route::get('/create', [LuckyWheelController::class, 'create'])->name('create');
    Route::post('/', [LuckyWheelController::class, 'store'])->name('store');
    Route::get('/{id}', [LuckyWheelController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [LuckyWheelController::class, 'edit'])->name('edit');
    Route::put('/{id}', [LuckyWheelController::class, 'update'])->name('update');
    Route::delete('/{id}', [LuckyWheelController::class, 'destroy'])->name('destroy');
});

