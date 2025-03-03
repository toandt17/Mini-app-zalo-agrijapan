<?php

use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\Api\CategoryController as ApiCategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('/loai-sp')->name('categories.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/them-moi', [CategoryController::class, 'add'])->name('add');
    Route::post('/them-moi', [CategoryController::class, 'store'])->name('store');
    Route::get('/chinh-sua/{id}', [CategoryController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/xoa/{id}', [CategoryController::class, 'delete'])->name('delete');
});

Route::get('/categories', [ApiCategoryController::class, 'index']);
Route::get('/top-categories', [ApiCategoryController::class, 'getTopCategories']);
