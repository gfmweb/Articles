<?php

namespace App\Modules\Articles\Application\Queries;

use App\Modules\Articles\Persistence\Interfaces\ArticleRepositoryInterface;
use App\Modules\Articles\Persistence\ORM\Article;

readonly class GetArticleQuery
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository
    ) {
    }

    public function execute(int $id, ?int $userId = null): ?Article
    {
        $article = $this->articleRepository->findByIdWithComments($id);

        if ($article && $userId) {
            $article->setAttribute('is_author', $article->author_id === $userId);
            $article->setAttribute('has_commented', $article->comments->where('author_id', $userId)->isNotEmpty());
        }

        return $article;
    }
}
