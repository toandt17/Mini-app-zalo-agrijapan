<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ZaloUserController;

// Các route cho Zalo Mini App
Route::post('/users/save', [ZaloUserController::class, 'saveUser']);
Route::post('/users/phone', [ZaloUserController::class, 'processPhoneToken']);
Route::post('/zalo/process-phone-token', [ZaloUserController::class, 'processPhoneToken']);
