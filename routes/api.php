<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BlogController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\LikeController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // 1. LOGIN
    Route::post('login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        // 2. USER-CREATE
        Route::post('users', [UserController::class, 'store']);
        // 3. USER-EDIT
        Route::put('user/profile', [UserController::class, 'updateSelf']);
        // Extra: Profile Get
        Route::get('user', [UserController::class, 'me']);

        // 4. BLOG-CREATE
        Route::post('blogs', [BlogController::class, 'store']);
        // 5. BLOG-LIST
        Route::get('blogs', [BlogController::class, 'index']);
        // 6. BLOG-EDIT
        Route::put('blogs/{blog}', [BlogController::class, 'update']);
        // 7. BLOG-DELETE
        Route::delete('blogs/{blog}', [BlogController::class, 'destroy']);

        // 8. COMMENT-CREATE
        Route::post('blogs/{blog}/comments', [CommentController::class, 'store']);
        // 9. COMMENT-LIST
        Route::get('blogs/{blog}/comments', [CommentController::class, 'index']);
        // 10. COMMENT-DELETE
        Route::delete('blogs/{blog}/comments/{comment}', [CommentController::class, 'destroy']);

        // 11. LIKE-TOGGLE
        Route::post('likes/toggle', [LikeController::class, 'toggle']);
    });
});




