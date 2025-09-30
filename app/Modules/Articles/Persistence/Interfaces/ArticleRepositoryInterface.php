<?php

namespace App\Modules\Articles\Persistence\Interfaces;

use App\Modules\Articles\Persistence\ORM\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ArticleRepositoryInterface
{
    public function create(array $data): Article;

    public function findById(int $id): ?Article;

    public function findByIdWithComments(int $id): ?Article;

    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator;

    public function getAll(): Collection;

    public function update(Article $article, array $data): Article;

    public function delete(Article $article): bool;
}
