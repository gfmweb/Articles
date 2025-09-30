<?php

namespace App\Modules\Articles\Application\Actions;

use App\Modules\Articles\Persistence\Interfaces\ArticleRepositoryInterface;
use App\Modules\Articles\Persistence\ORM\Article;

class DeleteArticleAction
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository
    ) {}

    public function execute(Article $article): bool
    {
        return $this->articleRepository->delete($article);
    }
}
