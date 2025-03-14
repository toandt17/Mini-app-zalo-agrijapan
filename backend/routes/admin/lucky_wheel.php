<?php

use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\GameController as ApiGameController;
use Illuminate\Support\Facades\Route;

Route::prefix('/game')->name('game.')->group(function () {
    Route::get('/', [GameController::class, 'index_lucky'])->name('index_lucky');
    Route::get('/them-moi', [GameController::class, 'add_lucky'])->name('add_lucky');
    Route::post('/them-moi', [GameController::class, 'store_lucky'])->name('store_lucky');
    Route::get('/chinh-sua/{id}', [GameController::class, 'edit_lucky'])->name('edit_lucky');
    Route::put('/update/{id}', [GameController::class, 'update_lucky'])->name('update_lucky');
    // Route::delete('/xoa/{id}', [CategoryController::class, 'delete'])->name('delete');
});

