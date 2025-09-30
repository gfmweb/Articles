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
            'article.owner' => \App\Modules\Articles\Presentation\Middlewares\CheckArticleOwnership::class,
            'comment.owner' => \App\Modules\Comments\Presentation\Middlewares\CheckCommentOwnership::class,
            'throttle.auth' => \App\Modules\Users\Presentation\Middlewares\ThrottleAuth::class,
            'throttle.article' => \App\Modules\Articles\Presentation\Middlewares\ThrottleArticleCreation::class,
            'throttle.comment' => \App\Modules\Comments\Presentation\Middlewares\ThrottleCommentCreation::class,
            'security.log' => \App\Modules\Security\Presentation\Middlewares\SecurityLogging::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
