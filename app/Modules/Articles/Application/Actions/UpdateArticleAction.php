<?php

namespace App\Modules\Articles\Application\Actions;

use App\Modules\Articles\Application\DTOs\UpdateArticleDTO;
use App\Modules\Articles\Persistence\Interfaces\ArticleRepositoryInterface;
use App\Modules\Articles\Persistence\ORM\Article;

class UpdateArticleAction
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository
    ) {
    }

    public function execute(Article $article, UpdateArticleDTO $dto): Article
    {
        $data = array_filter([
            'title' => $dto->title,
            'content' => $dto->content,
        ], fn ($value) => $value !== null);

        return $this->articleRepository->update($article, $data);
    }
}
