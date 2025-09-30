<?php

use App\Modules\Articles\Presentation\Controllers\ArticleController;
use App\Modules\Comments\Presentation\Controllers\CommentController;
use App\Modules\Users\Presentation\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Auth routes with rate limiting
Route::middleware(['throttle.auth', 'security.log'])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

// Articles routes
Route::get('articles', [ArticleController::class, 'index']);
Route::get('articles/{id}', [ArticleController::class, 'show']);
Route::post('articles', [ArticleController::class, 'store'])->middleware(['auth:sanctum', 'throttle.article', 'security.log']);
Route::put('articles/{article}', [ArticleController::class, 'update'])->middleware(['auth:sanctum', 'article.owner']);
Route::delete('articles/{article}', [ArticleController::class, 'destroy'])->middleware(['auth:sanctum', 'article.owner']);

// Comments routes
Route::post('articles/{articleId}/comments', [CommentController::class, 'store'])->middleware(['auth:sanctum', 'throttle.comment', 'security.log']);
Route::delete('comments/{id}', [CommentController::class, 'destroy'])->middleware(['auth:sanctum', 'comment.owner']);
