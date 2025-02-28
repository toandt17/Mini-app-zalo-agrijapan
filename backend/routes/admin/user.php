<?php

use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\User\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/nguoi-dung')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/them-moi', [UserController::class, 'add'])->name('add');
    Route::get('/chinh-sua/{id}', [UserController::class, 'edit'])->name('edit');
});

Route::prefix('/tai-khoan-cao-cap')->name('admin.')->group(function () {
    Route::get('/', [UserController::class, 'indexAdmin'])->name('index');
    Route::get('/them-moi', [UserController::class, 'addAdmin'])->name('add');
    Route::get('/chinh-sua/{id}', [UserController::class, 'editAdmin'])->name('edit');
});
