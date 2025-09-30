<?php

namespace App\Modules\Users\Providers;

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
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->singleton(CreateUserAction::class);
        $this->app->singleton(UpdateUserAction::class);
        $this->app->singleton(DeleteUserAction::class);
        $this->app->singleton(RegisterUserAction::class);
        $this->app->singleton(LoginUserAction::class);
        $this->app->singleton(GetUserQuery::class);
        $this->app->singleton(GetUsersQuery::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Presentation/routes/users.php');

        $this->loadTranslationsFrom(
            __DIR__.'/../Persistence/resources/lang',
            'users'
        );
    }
}
