<?php

use Illuminate\Support\Facades\Route;

// SPA роут - все запросы к фронтенду направляются на главную страницу
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
