<?php

use App\Http\Controllers\Admin\categories\CategoriesController;
use Illuminate\Support\Facades\Route;

Route::prefix('/loai-sp')->name('categories.')->group(function () {
    Route::get('/', [CategoriesController::class, 'index'])->name('index');
    Route::get('/them-moi', [CategoriesController::class, 'add'])->name('add');
    Route::get('/chinh-sua', [CategoriesController::class, 'edit'])->name('edit');

});
