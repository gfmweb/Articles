<?php

use App\Modules\Articles\Presentation\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    // Public routes
    Route::get('articles', [ArticleController::class, 'index']);
    Route::get('articles/{id}', [ArticleController::class, 'show']);

    // Protected routes
    Route::middleware(['auth:sanctum', 'throttle.article', 'security.log'])->group(function () {
        Route::post('articles', [ArticleController::class, 'store']);
    });

    Route::middleware(['auth:sanctum', 'article.owner'])->group(function () {
        Route::put('articles/{id}', [ArticleController::class, 'update']);
        Route::delete('articles/{id}', [ArticleController::class, 'destroy']);
    });
});
