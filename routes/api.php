<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\WriterController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CommonController;

// Admin routes
Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminController::class, 'login']);
    
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::post('/logout', [AdminController::class, 'logout']);
        // Thêm các routes admin khác ở đây
    });
});

// Writer routes
Route::prefix('writer')->group(function () {
    Route::post('/login', [WriterController::class, 'login']);
    
    Route::middleware(['auth:sanctum', 'role:writer'])->group(function () {
        Route::post('/logout', [WriterController::class, 'logout']);
        // Thêm các routes writer khác ở đây
    });
});

// User routes
Route::post('/login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum', 'role:user'])->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    // Thêm các routes user khác ở đây
});

Route::get('/', [CommonController::class, 'getPostsForHomepage']);
Route::get('/news', [CommonController::class, 'getNewsPosts']);
Route::get('/events', [CommonController::class, 'getEventsPosts']);
Route::get('/clubs', [CommonController::class, 'getClubsPosts']);
Route::get('/student-life', [CommonController::class, 'getStudentLifePosts']);