<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Writer\WriterController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\CommonController;

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
Route::get('/news', [CommonController::class, 'getAllNewsPosts']);
Route::get('/events', [CommonController::class, 'getAllEventsPosts']);
Route::get('/clubs', [CommonController::class, 'getAllClubsPosts']);
Route::get('/student-life', [CommonController::class, 'getAllStudentLifePosts']);
Route::get('/posts/{id}', [CommonController::class, 'getPostDetails']);