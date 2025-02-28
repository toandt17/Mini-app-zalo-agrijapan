<?php

use App\Http\Controllers\Admin\Chart\ChartController;
use Illuminate\Support\Facades\Route;

Route::prefix('/thong-ke')->name('chart.')->group(function () {
    Route::get('/', [ChartController::class, 'index'])->name('index');
    Route::get('/them-moi', [ChartController::class, 'add'])->name('add');
    Route::get('/chinh-sua', [ChartController::class, 'edit'])->name('edit');
});


