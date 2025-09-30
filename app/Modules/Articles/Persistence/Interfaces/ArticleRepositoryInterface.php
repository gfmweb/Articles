<?php

namespace App\Modules\Articles\Persistence\Interfaces;

use App\Modules\Articles\Persistence\ORM\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ArticleRepositoryInterface
{
    /**
     * Create a new article.
     */
    public function create(array $data): Article;

    /**
     * Find article by ID.
     */
    public function findById(int $id): ?Article;

    /**
     * Find article by ID with comments.
     */
    public function findByIdWithComments(int $id): ?Article;

    /**
     * Get all articles with pagination.
     */
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get all articles.
     */
    public function getAll(): Collection;

    /**
     * Update article.
     */
    public function update(Article $article, array $data): Article;

    /**
     * Delete article.
     */
    public function delete(Article $article): bool;
}
