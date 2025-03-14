<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ZaloUserController;
use App\Http\Controllers\Api\GameController;

// Các route cho Zalo Mini App
Route::post('/users/save', [ZaloUserController::class, 'saveUser']);
Route::post('/users/phone', [ZaloUserController::class, 'processPhoneToken']);
Route::post('/zalo/process-phone-token', [ZaloUserController::class, 'processPhoneToken']);


// Các route cho các trò chơi
Route::get('/games/lucky_wheel', [GameController::class, 'index']);




