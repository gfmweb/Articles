<?php

namespace App\Modules\Articles\Presentation\Container;

use App\Modules\Articles\Application\Actions\CreateArticleAction;
use App\Modules\Articles\Application\Actions\DeleteArticleAction;
use App\Modules\Articles\Application\Actions\UpdateArticleAction;
use App\Modules\Articles\Application\Queries\GetArticleQuery;
use App\Modules\Articles\Application\Queries\GetArticlesQuery;
use App\Modules\Articles\Infrastructure\Repositories\ArticleRepository;
use App\Modules\Articles\Persistence\Interfaces\ArticleRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class ArticlesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);

        $this->app->singleton(CreateArticleAction::class);
        $this->app->singleton(UpdateArticleAction::class);
        $this->app->singleton(DeleteArticleAction::class);
        $this->app->singleton(GetArticleQuery::class);
        $this->app->singleton(GetArticlesQuery::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
