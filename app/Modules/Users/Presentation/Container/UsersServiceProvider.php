<?php

namespace App\Modules\Users\Presentation\Container;

use App\Modules\Users\Application\Actions\CreateUserAction;
use App\Modules\Users\Application\Actions\DeleteUserAction;
use App\Modules\Users\Application\Actions\LoginUserAction;
use App\Modules\Users\Application\Actions\RegisterUserAction;
use App\Modules\Users\Application\Actions\UpdateUserAction;
use App\Modules\Users\Application\Queries\GetUserQuery;
use App\Modules\Users\Application\Queries\GetUsersQuery;
use App\Modules\Users\Infrastructure\Repositories\UserRepository;
use App\Modules\Users\Persistence\Interfaces\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class UsersServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register repository
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // Register actions
        $this->app->singleton(CreateUserAction::class, function ($app) {
            return new CreateUserAction($app->make(UserRepositoryInterface::class));
        });

        $this->app->singleton(UpdateUserAction::class, function ($app) {
            return new UpdateUserAction($app->make(UserRepositoryInterface::class));
        });

        $this->app->singleton(DeleteUserAction::class, function ($app) {
            return new DeleteUserAction($app->make(UserRepositoryInterface::class));
        });

        $this->app->singleton(RegisterUserAction::class, function ($app) {
            return new RegisterUserAction($app->make(UserRepositoryInterface::class));
        });

        $this->app->singleton(LoginUserAction::class, function ($app) {
            return new LoginUserAction($app->make(UserRepositoryInterface::class));
        });

        // Register queries
        $this->app->singleton(GetUserQuery::class, function ($app) {
            return new GetUserQuery($app->make(UserRepositoryInterface::class));
        });

        $this->app->singleton(GetUsersQuery::class, function ($app) {
            return new GetUsersQuery($app->make(UserRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load translations
        $this->loadTranslationsFrom(
            __DIR__.'/../../Persistence/resources/lang',
            'users'
        );
    }
}
