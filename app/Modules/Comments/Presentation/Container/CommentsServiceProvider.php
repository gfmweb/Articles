<?php

namespace App\Modules\Comments\Presentation\Container;

use App\Modules\Comments\Application\Actions\CreateCommentAction;
use App\Modules\Comments\Application\Actions\DeleteCommentAction;
use App\Modules\Comments\Application\Queries\GetCommentsByArticleQuery;
use App\Modules\Comments\Infrastructure\Repositories\CommentRepository;
use App\Modules\Comments\Persistence\Interfaces\CommentRepositoryInterface;
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
        $this->app->singleton(DeleteCommentAction::class);
        $this->app->singleton(GetCommentsByArticleQuery::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
