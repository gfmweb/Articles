<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'article.owner' => \App\Http\Middleware\CheckArticleOwnership::class,
            'comment.owner' => \App\Http\Middleware\CheckCommentOwnership::class,
            'throttle.auth' => \App\Http\Middleware\ThrottleAuth::class,
            'throttle.article' => \App\Http\Middleware\ThrottleArticleCreation::class,
            'throttle.comment' => \App\Http\Middleware\ThrottleCommentCreation::class,
            'security.log' => \App\Http\Middleware\SecurityLogging::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
