<?php

namespace Tests;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        // Загружаем ServiceProvider'ы модулей для тестов ДО bootstrap
        // Это необходимо, так как в тестовом окружении ServiceProvider'ы не загружаются автоматически
        $app->register(\App\Modules\Users\Providers\UsersServiceProvider::class);
        $app->register(\App\Modules\Articles\Providers\ArticlesServiceProvider::class);
        $app->register(\App\Modules\Comments\Providers\CommentsServiceProvider::class);
        $app->register(\App\Modules\Security\Providers\SecurityServiceProvider::class);

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }
}
