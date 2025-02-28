<?php

use App\Http\Controllers\Admin\Product\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('/san-pham')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/them-moi', [ProductController::class, 'add'])->name('add');
    Route::get('/chinh-sua/{id}', [ProductController::class, 'edit'])->name('edit');
});

Route::get('/products', [ProductController::class, 'getProducts'])->name('getProducts');
Route::get('/export-json', [ProductController::class, 'exportToJson'])->name('exportToJson');
