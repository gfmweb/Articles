<?php

use App\Modules\Articles\Presentation\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    // Public routes (with optional auth for highlighting)
    Route::get('articles', [ArticleController::class, 'index']);
    Route::get('articles/{id}', [ArticleController::class, 'show']);

    // Protected routes
    Route::middleware(['auth:sanctum', 'throttle.article', 'security.log'])->group(function () {
        Route::post('articles', [ArticleController::class, 'store']);
    });

    // Routes with manual auth handling
    Route::put('articles/{article}', [ArticleController::class, 'update']);
    Route::delete('articles/{article}', [ArticleController::class, 'destroy']);
});
