<?php

namespace App\Modules\Articles\Application\Queries;

use App\Modules\Articles\Persistence\Interfaces\ArticleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class GetArticlesQuery
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository
    ) {
    }

    public function execute(int $perPage = 15, ?int $userId = null): LengthAwarePaginator
    {
        return $this->articleRepository->getAllPaginated($perPage, $userId);
    }
}
