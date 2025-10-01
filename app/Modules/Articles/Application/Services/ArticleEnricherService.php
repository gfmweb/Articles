<?php

namespace App\Modules\Articles\Application\Services;

use App\Modules\Articles\Persistence\ORM\Article;

readonly class ArticleEnricherService
{
    /**
     * Добавляет вычисляемые поля к статье для текущего пользователя
     */
    public function enrichArticle(Article $article, int $userId): void
    {
        $article->setAttribute('is_author', $article->author_id === $userId);
        $article->setAttribute('has_commented', $this->hasUserCommented($article, $userId));
    }

    /**
     * Проверяет, комментировал ли пользователь статью
     */
    private function hasUserCommented(Article $article, int $userId): bool
    {
        if (! $article->relationLoaded('comments')) {
            return false;
        }

        return $article->comments->where('author_id', $userId)->isNotEmpty();
    }
}

