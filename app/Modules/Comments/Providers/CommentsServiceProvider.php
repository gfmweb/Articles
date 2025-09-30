<?php

namespace App\Modules\Comments\Providers;

use App\Modules\Comments\Application\Actions\CreateCommentAction;
use App\Modules\Comments\Application\Actions\DeleteCommentAction;
use App\Modules\Comments\Application\Actions\UpdateCommentAction;
use App\Modules\Comments\Application\Queries\GetCommentsByArticleQuery;
use App\Modules\Comments\Domain\Policies\CommentPolicy;
use App\Modules\Comments\Infrastructure\Repositories\CommentRepository;
use App\Modules\Comments\Persistence\Interfaces\CommentRepositoryInterface;
use App\Modules\Comments\Persistence\ORM\Comment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class CommentsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);

        $this->app->singleton(CreateCommentAction::class);
        $this->app->singleton(UpdateCommentAction::class);
        $this->app->singleton(DeleteCommentAction::class);
        $this->app->singleton(GetCommentsByArticleQuery::class);
        $this->app->singleton(\App\Modules\Comments\Application\Queries\GetCommentQuery::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../Presentation/routes/comments.php');

        // Load translations
        $this->loadTranslationsFrom(
            __DIR__.'/../Persistence/resources/lang',
            'comments'
        );

        // Register policies
        Gate::policy(Comment::class, CommentPolicy::class);
    }
}
