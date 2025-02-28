<?php

use App\Http\Controllers\Admin\Preview\PreviewController;
use Illuminate\Support\Facades\Route;

Route::prefix('/danh-gia')->name('preview.')->group(function () {
    Route::get('/', [PreviewController::class, 'index'])->name('index');
    Route::get('/them-moi', [PreviewController::class, 'add'])->name('add');
    Route::get('/chinh-sua', [PreviewController::class, 'edit'])->name('edit');
});
