<?php

namespace App\Modules\Articles\Application\Actions;

use App\Modules\Articles\Application\DTOs\CreateArticleDTO;
use App\Modules\Articles\Persistence\Interfaces\ArticleRepositoryInterface;
use App\Modules\Articles\Persistence\ORM\Article;

class CreateArticleAction
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository
    ) {}

    public function execute(CreateArticleDTO $dto): Article
    {
        return $this->articleRepository->create([
            'title' => $dto->title,
            'content' => $dto->content,
            'author_id' => $dto->author_id,
        ]);
    }
}
