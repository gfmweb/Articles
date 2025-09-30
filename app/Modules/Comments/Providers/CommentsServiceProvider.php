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

        $this->app->bind(CreateCommentAction::class);
        $this->app->bind(UpdateCommentAction::class);
        $this->app->bind(DeleteCommentAction::class);
        $this->app->bind(GetCommentsByArticleQuery::class);
        $this->app->bind(\App\Modules\Comments\Application\Queries\GetCommentQuery::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Presentation/routes/comments.php');

        $this->loadTranslationsFrom(
            __DIR__.'/../Persistence/resources/lang',
            'comments'
        );
        Gate::policy(Comment::class, CommentPolicy::class);
    }
}
