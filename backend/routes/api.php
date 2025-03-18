<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ZaloUserController;
use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\QrCodeController;
use App\Http\Controllers\Api\BarcodeController;

// Các route cho Zalo Mini App
Route::post('/users/save', [ZaloUserController::class, 'saveUser']);
Route::post('/users/phone', [ZaloUserController::class, 'processPhoneToken']);
Route::post('/zalo/process-phone-token', [ZaloUserController::class, 'processPhoneToken']);
Route::post('/zalo/process-location-token', [ZaloUserController::class, 'processLocationToken']);
// Các route cho đại lý
Route::get('/agents', [AgentController::class, 'index']);
Route::get('/agents/{id}', [AgentController::class, 'show']);
Route::post('/agents/nearest', [AgentController::class, 'findNearest']);
// Route để xử lý khi quét mã QR cho API backend
Route::get('/agent/detail/{id}', [QrCodeController::class, 'showAgentDetail'])->name('agent.detail');
// Lưu ý: Route frontend /agents/:id xử lý bởi front-end (thiepcuoitoandao.id.vn)

// Các route cho QR Code
Route::get('/qrcode/info', [QrCodeController::class, 'getQrInfo']);
Route::get('/qrcode/history/{agentId}', [QrCodeController::class, 'getQrHistory']);

// Route for direct access from QR code scans - displays HTML view
Route::get('/agent/qr/{id}', [QrCodeController::class, 'viewAgentFromQr'])->name('agent.qr.view');

// Các route cho vị trí
Route::get('/provinces', [LocationController::class, 'getProvinces']);
Route::get('/provinces/{provinceId}/districts', [LocationController::class, 'getDistricts']);
Route::get('/districts/{districtId}/wards', [LocationController::class, 'getWards']);
Route::get('/location/data', [LocationController::class, 'getLocationData']);
Route::post('/location/nearest', [LocationController::class, 'findNearestLocation']);

// Thêm routes đơn giản cho quận/huyện và phường/xã
Route::get('/districts/{provinceId}', [LocationController::class, 'getDistricts']);
Route::get('/wards/{districtId}', [LocationController::class, 'getWards']);

// Các route cho địa chỉ
Route::get('/address/full', [AddressController::class, 'getFullAddress']);

// Route test
Route::get('/test-relations', function() {
    $agent = App\Models\Agent::with(['province', 'district', 'ward'])->first();
    if (!$agent) return ['error' => 'Không tìm thấy đại lý'];

    return [
        'agent' => $agent->name,
        'province' => $agent->province ? $agent->province->name : null,
        'district' => $agent->district ? $agent->district->name : null,
        'ward' => $agent->ward ? $agent->ward->name : null,
        'full_address' => $agent->full_address
    ];
});

// Test route to check if API is working
Route::get('/test', function() {
    return response()->json([
        'message' => 'API is working!',
        'timestamp' => now()->toDateTimeString()
    ]);
});

// Check database connection
Route::get('/db-check', function() {
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        return response()->json([
            'message' => 'Database connection successful',
            'connection' => \Illuminate\Support\Facades\DB::connection()->getDatabaseName()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Database connection failed',
            'error' => $e->getMessage()
        ], 500);
    }
});

// Test route to check provinces directly
Route::get('/test-provinces', function() {
    $provinces = \App\Models\Province::all();
    return response()->json([
        'count' => $provinces->count(),
        'provinces' => $provinces
    ]);
});

// Test route to check districts directly
Route::get('/test-districts', function() {
    $districts = \App\Models\District::all();
    return response()->json([
        'count' => $districts->count(),
        'districts' => $districts->take(10)
    ]);
});

// Test route to check wards directly
Route::get('/test-wards', function() {
    $wards = \App\Models\Ward::all();
    return response()->json([
        'count' => $wards->count(),
        'wards' => $wards->take(10)
    ]);
});

// Test route to check agent with relations
Route::get('/test-agent-relations', function() {
    $agent = \App\Models\Agent::first();
    if (!$agent) return ['error' => 'Không tìm thấy đại lý'];

    $agent->load(['province', 'district', 'ward']);

    return [
        'agent' => $agent,
        'province_exists' => $agent->province ? true : false,
        'district_exists' => $agent->district ? true : false,
        'ward_exists' => $agent->ward ? true : false,
    ];
});

// Test route for Zalo controllers
Route::get('/zalo/test', function() {
    return [
        'status' => 'ok',
        'message' => 'Zalo API endpoint test is working',
        'timestamp' => now()->toIso8601String()
    ];
});

// Route to see all registered routes for debugging
Route::get('/debug/routes', function() {
    $routes = collect(Route::getRoutes())->map(function ($route) {
        return [
            'methods' => $route->methods(),
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'action' => $route->getActionName(),
        ];
    });

    return [
        'status' => 'ok',
        'routes' => $routes->toArray(),
        'count' => $routes->count()
    ];
});


// Các route cho các trò chơi
Route::get('/games/lucky_wheel', [GameController::class, 'index']);

// API routes for barcode verification
Route::prefix('barcode')->group(function () {
    Route::get('/verify/{barcodeValue}', [BarcodeController::class, 'verify']);
});




