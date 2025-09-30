<?php

use App\Modules\Users\Presentation\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Users Routes
|--------------------------------------------------------------------------
|
| Here are the routes for the Users module. These routes are loaded
| by the RouteServiceProvider within a group which contains the "api" middleware group.
|
*/

Route::prefix('api')->group(function () {
    // Auth routes with rate limiting
    Route::middleware(['throttle.auth', 'security.log'])->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Protected auth routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});
