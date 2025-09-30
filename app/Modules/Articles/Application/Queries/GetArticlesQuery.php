<?php

namespace App\Modules\Articles\Application\Queries;

use App\Modules\Articles\Persistence\Interfaces\ArticleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetArticlesQuery
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articleRepository
    ) {}

    public function execute(int $perPage = 15, ?int $userId = null): LengthAwarePaginator
    {
        return $this->articleRepository->getAllPaginated($perPage, $userId);
    }
}
