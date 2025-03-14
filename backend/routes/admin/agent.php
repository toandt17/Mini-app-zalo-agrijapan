<?php

use App\Http\Controllers\Admin\Agent\AgentController;
use App\Http\Controllers\Api\AgentController as ApiAgentController;
use Illuminate\Support\Facades\Route;

Route::prefix('/dai-ly')->name('agents.')->group(function () {
    Route::get('/', [AgentController::class, 'index'])->name('index');
    Route::get('/them-moi', [AgentController::class, 'add'])->name('add');
    Route::post('/them-moi', [AgentController::class, 'store'])->name('store');
    Route::get('/chinh-sua/{id}', [AgentController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [AgentController::class, 'update'])->name('update');
    Route::delete('/xoa/{id}', [AgentController::class, 'delete'])->name('delete');
});

Route::get('/agents', [ApiAgentController::class, 'index']);
Route::get('/top-agents', [ApiAgentController::class, 'getTopAgents']);
