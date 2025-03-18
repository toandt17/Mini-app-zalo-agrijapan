<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Game\MissionController;

// Routes for mission management
Route::group(['prefix' => 'missions', 'as' => 'missions.'], function () {
    Route::get('/', [MissionController::class, 'index'])->name('index');
    Route::get('/create', [MissionController::class, 'create'])->name('create');
    Route::post('/', [MissionController::class, 'store'])->name('store');
    Route::get('/{id}', [MissionController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [MissionController::class, 'edit'])->name('edit');
    Route::put('/{id}', [MissionController::class, 'update'])->name('update');
    Route::delete('/{id}', [MissionController::class, 'destroy'])->name('destroy');
});
