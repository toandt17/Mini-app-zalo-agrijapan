<?php

use App\Http\Controllers\Admin\Contact\ContactController;
use Illuminate\Support\Facades\Route;

Route::prefix('/yeu-cau-dai-ly')->name('contact_sales.')->group(function () {
    Route::get('/', [ContactController::class, 'index_sales'])->name('index_sales');
    Route::get('/them-moi', [ContactController::class, 'add_sales'])->name('add_sales');
    Route::get('/chinh-sua', [ContactController::class, 'edit_sales'])->name('edit_sales');
});

Route::prefix('/tu-van-ky-thuat')->name('contact_tech.')->group(function () {
    Route::get('/', [ContactController::class, 'index_tech'])->name('index_tech');
    Route::get('/them-moi', [ContactController::class, 'add_tech'])->name('add_tech');
    Route::get('/chinh-sua', [ContactController::class, 'edit_tech'])->name('edit_tech');
});
