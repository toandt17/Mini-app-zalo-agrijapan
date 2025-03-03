<?php

use App\Http\Controllers\Admin\Product\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('/san-pham')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/them-moi', [ProductController::class, 'add'])->name('add');
    Route::post('/them-moi', [ProductController::class, 'store'])->name('store');
    Route::get('/chinh-sua/{id}', [ProductController::class, 'edit'])->name('edit');
    Route::put('/cap-nhat/{id}', [ProductController::class, 'update'])->name('update');
    Route::delete('/xoa/{id}', [ProductController::class, 'delete'])->name('delete');
});
// Lấy dữ liệu sản phẩm
Route::get('/products', [ProductController::class, 'getProducts'])->name('getProducts');
Route::get('/products/category/{category_id}', [ProductController::class, 'getProductsByCategory'])->name('getProductsByCategory');
Route::get('/export-json', [ProductController::class, 'exportToJson'])->name('exportToJson');
Route::get('/products/search', [ProductController::class, 'searchProducts'])->name('searchProducts');
