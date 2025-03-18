<?php

use App\Http\Controllers\Admin\Game\CheckinController;
use Illuminate\Support\Facades\Route;

// Routes cho quản lý điểm danh
Route::group(['prefix' => 'checkin', 'as' => 'checkin.'], function () {
    Route::get('/', [CheckinController::class, 'index'])->name('index');
    Route::get('/reports', [CheckinController::class, 'reports'])->name('reports');
    Route::get('/settings', [CheckinController::class, 'settings'])->name('settings');
    Route::post('/settings', [CheckinController::class, 'saveSettings'])->name('save-settings');
    Route::get('/history/{userId?}', [CheckinController::class, 'history'])->name('history');
});
