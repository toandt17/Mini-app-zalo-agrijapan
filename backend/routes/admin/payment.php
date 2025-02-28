<?php

use App\Http\Controllers\Admin\Payment\PaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix('/thanh-toan')->name('payment.')->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('index');
    Route::get('/them-moi', [PaymentController::class, 'add'])->name('add');
    Route::get('/chinh-sua', [PaymentController::class, 'edit'])->name('edit');
});
