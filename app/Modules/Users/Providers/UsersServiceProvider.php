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

        $this->app->bind(CreateUserAction::class);
        $this->app->bind(UpdateUserAction::class);
        $this->app->bind(DeleteUserAction::class);
        $this->app->bind(RegisterUserAction::class);
        $this->app->bind(LoginUserAction::class);
        $this->app->bind(GetUserQuery::class);
        $this->app->bind(GetUsersQuery::class);
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
