<?php

namespace App\Modules\Articles\Providers;

use App\Modules\Articles\Application\Actions\CreateArticleAction;
use App\Modules\Articles\Application\Actions\DeleteArticleAction;
use App\Modules\Articles\Application\Actions\UpdateArticleAction;
use App\Modules\Articles\Application\Queries\GetArticleQuery;
use App\Modules\Articles\Application\Queries\GetArticlesQuery;
use App\Modules\Articles\Domain\Policies\ArticlePolicy;
use App\Modules\Articles\Infrastructure\Repositories\ArticleRepository;
use App\Modules\Articles\Persistence\Interfaces\ArticleRepositoryInterface;
use App\Modules\Articles\Persistence\ORM\Article;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class ArticlesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
        $this->app->bind(CreateArticleAction::class);
        $this->app->bind(UpdateArticleAction::class);
        $this->app->bind(DeleteArticleAction::class);
        $this->app->bind(GetArticleQuery::class);
        $this->app->bind(GetArticlesQuery::class);
    }

    public function boot(): void
    {

        $this->loadRoutesFrom(__DIR__.'/../Presentation/routes/articles.php');
        $this->loadTranslationsFrom(
            __DIR__.'/../Persistence/resources/lang',
            'articles'
        );
        Gate::policy(Article::class, ArticlePolicy::class);
    }
}
