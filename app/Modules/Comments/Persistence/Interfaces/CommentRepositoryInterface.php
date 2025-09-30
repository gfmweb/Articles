<?php

namespace App\Modules\Comments\Persistence\Interfaces;

use App\Modules\Comments\Persistence\ORM\Comment;
use Illuminate\Database\Eloquent\Collection;

interface CommentRepositoryInterface
{
    public function create(array $data): Comment;

    public function findById(int $id): ?Comment;

    public function getByArticleId(int $articleId): Collection;

    public function update(Comment $comment, array $data): Comment;

    public function delete(Comment $comment): bool;
}
