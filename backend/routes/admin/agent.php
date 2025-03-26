<?php

use App\Http\Controllers\Admin\Agent\AgentController;
use App\Http\Controllers\Api\AgentController as ApiAgentController;
use App\Http\Controllers\BarcodeViewController;
use App\Http\Controllers\QrCodeScanController;
use Illuminate\Support\Facades\Route;

Route::prefix('/dai-ly')->name('agents.')->group(function () {
    Route::get('/', [AgentController::class, 'index'])->name('index');
    Route::get('/them-moi', [AgentController::class, 'add'])->name('add');
    Route::post('/them-moi', [AgentController::class, 'store'])->name('store');
    Route::get('/chi-tiet/{id}', [AgentController::class, 'show'])->name('show');
    Route::get('/chinh-sua/{id}', [AgentController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [AgentController::class, 'update'])->name('update');
    Route::delete('/xoa/{id}', [AgentController::class, 'delete'])->name('delete');
    Route::get('/tao-lai-qr/{id}', [AgentController::class, 'regenerateQrCode'])->name('regenerate-qr');
    Route::get('/in-ma-qr', [AgentController::class, 'printQrCodes'])->name('print-qr-codes');
    Route::get('/in-ma-qr/{id}', [AgentController::class, 'printSingleQrCode'])->name('print-single-qr-code');

    // Routes cho quản lý lịch sử mã QR
    Route::get('/lich-su-ma-qr/{id}', [AgentController::class, 'qrCodeHistory'])->name('qr-history');
    Route::get('/vo-hieu-hoa-ma-qr/{id}/{qrCodeId}', [AgentController::class, 'deactivateQrCode'])->name('deactivate-qr');
    Route::get('/kich-hoat-ma-qr/{id}/{qrCodeId}', [AgentController::class, 'activateQrCode'])->name('activate-qr');
    Route::get('/vo-hieu-hoa-tat-ca-ma-qr/{id}', [AgentController::class, 'deactivateAllQrCodes'])->name('deactivate-all-qr');

    // Routes cho quản lý mã barcode
    Route::get('/lich-su-ma-barcode/{id}', [AgentController::class, 'barcodeHistory'])->name('barcode-history');
    Route::get('/tao-ma-barcode/{id}', [AgentController::class, 'generateBarcode'])->name('generate-barcode');
    Route::post('/tao-ma-barcode-voi-don-hang/{id}', [AgentController::class, 'generateBarcodeWithOrder'])->name('generate-barcode-with-order');
    Route::get('/vo-hieu-hoa-ma-barcode/{id}/{barcodeId}', [AgentController::class, 'deactivateBarcode'])->name('deactivate-barcode');
    Route::get('/kich-hoat-ma-barcode/{id}/{barcodeId}', [AgentController::class, 'activateBarcode'])->name('activate-barcode');
    Route::get('/in-ma-barcode/{id}/{barcodeId?}', [AgentController::class, 'printBarcode'])->name('print-barcode');

    // Route mới cho tra cứu EAN-13 barcode
    Route::get('/barcode-ean13/{barcode}', [BarcodeViewController::class, 'showBarcodeDetail'])->name('barcode-ean13-detail');

    // Route mới cho trang tìm kiếm barcode trong admin
    Route::get('/tim-kiem-barcode', [AgentController::class, 'searchBarcode'])->name('search-barcode');
    Route::post('/tim-kiem-barcode', [AgentController::class, 'processBarcodeSearch'])->name('process-barcode-search');

    // Routes cho chi tiết lượt quét mã QR
    Route::get('/chi-tiet-luot-quet-ma-qr/{id}/{qrCodeId}', [AgentController::class, 'qrScanDetails'])->name('qr-scan-details');
});

// Route cho API hiển thị chi tiết barcode EAN-13 (không yêu cầu xác thực)
Route::get('/barcode-ean13/{barcode}', [BarcodeViewController::class, 'showBarcodeDetail'])->name('barcode-ean13-public');

Route::get('/agents', [ApiAgentController::class, 'index']);
Route::get('/top-agents', [ApiAgentController::class, 'getTopAgents']);

// Routes cho việc quét mã QR
Route::get('/agent/qr/{token}/scan', [QrCodeScanController::class, 'scan'])->name('agent.qr.scan');
Route::get('/agent/qr/{id}', [QrCodeScanController::class, 'viewAgent'])->name('agent.qr.view');


