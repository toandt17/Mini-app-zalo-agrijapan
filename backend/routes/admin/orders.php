<?php

use App\Http\Controllers\Admin\Order\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('/don-hang')->name('order.')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::get('/chinh-sua', [OrderController::class, 'edit'])->name('edit');
});
