<?php

use App\Http\Controllers\Admin\Game\QuestionController;
use Illuminate\Support\Facades\Route;

    // Quản lý câu hỏi quiz
    Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
    Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');

    // Lọc câu hỏi theo cấp độ khó (phải đặt trước route có tham số)
    Route::get('/questions/filter', [QuestionController::class, 'filterByLevel'])->name('questions.filter');

    Route::get('/questions/{id}', [QuestionController::class, 'show'])->name('questions.show');
    Route::get('/questions/{id}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('/questions/{id}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/{id}', [QuestionController::class, 'destroy'])->name('questions.destroy');
