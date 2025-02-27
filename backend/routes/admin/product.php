<?php

use App\Http\Controllers\Admin\Product\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('/san-pham')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');

});
