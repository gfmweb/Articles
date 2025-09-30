<?php

use App\Modules\Comments\Presentation\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Comments Routes
|--------------------------------------------------------------------------
|
| Here are the routes for the Comments module. These routes are loaded
| by the RouteServiceProvider within a group which contains the "api" middleware group.
|
*/

Route::prefix('api')->group(function () {
    // Public routes
    Route::get('articles/{articleId}/comments', [CommentController::class, 'index']);

    // Protected routes
    Route::middleware(['auth:sanctum', 'throttle.comment', 'security.log'])->group(function () {
        Route::post('articles/{articleId}/comments', [CommentController::class, 'store']);
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::put('comments/{id}', [CommentController::class, 'update']);
        Route::delete('comments/{id}', [CommentController::class, 'destroy']);
    });
});
