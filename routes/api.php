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
        Route::get('/posts', [AdminController::class, 'getAllPosts']);
        Route::get('/posts/{id}', [AdminController::class, 'getPostDetails']);
        Route::get('/posts/{id}/approve', [AdminController::class, 'approvePost']);
        Route::delete('/posts/{id}', [AdminController::class, 'deletePost']);
        Route::get('/writers', [AdminController::class, 'getWritersList']);
    });
});

// Writer routes
Route::prefix('writer')->group(function () {
    Route::post('/login', [WriterController::class, 'login']);
    
    Route::middleware(['auth:sanctum', 'role:writer'])->group(function () {
        Route::post('/logout', [WriterController::class, 'logout']);
        Route::get('/posts', [WriterController::class, 'getListPosts']);
        Route::post('/posts', [WriterController::class, 'createPost']);
        Route::get('/posts/{id}', [WriterController::class, 'getPostDetail']);
        Route::delete('/posts/{id}', [WriterController::class, 'deletePost']);
        Route::post('/upload/featured-image', [WriterController::class, 'uploadFeaturedImage']);
        Route::post('/upload/editor-image', [WriterController::class, 'uploadEditorImage']);
        Route::post('/uploadPdf', [WriterController::class, 'uploadPdf']);
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
Route::get('/posts/{id}/comments', [CommonController::class, 'getComments']);

// Protected routes for authenticated users
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/posts/{id}/comments', [CommonController::class, 'createComment']);
    Route::post('/posts/{id}/like', [CommonController::class, 'toggleLike']);
    Route::post('/posts/{id}/view', [CommonController::class, 'incrementView']);
});