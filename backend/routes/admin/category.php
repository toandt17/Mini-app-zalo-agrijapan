<?php

use App\Http\Controllers\Admin\Category\CategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('/loai-sp')->name('category.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/them-moi', [CategoryController::class, 'add'])->name('add');
    Route::get('/chinh-sua/{id}', [CategoryController::class, 'edit'])->name('edit');
});
