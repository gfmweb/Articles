<?php

namespace App\Modules\Comments\Persistence\Interfaces;

use App\Modules\Comments\Persistence\ORM\Comment;
use Illuminate\Database\Eloquent\Collection;

interface CommentRepositoryInterface
{
    /**
     * Create a new comment.
     */
    public function create(array $data): Comment;

    /**
     * Find comment by ID.
     */
    public function findById(int $id): ?Comment;

    /**
     * Get comments by article ID.
     */
    public function getByArticleId(int $articleId): Collection;

    /**
     * Update comment.
     */
    public function update(Comment $comment, array $data): Comment;

    /**
     * Delete comment.
     */
    public function delete(Comment $comment): bool;
}
