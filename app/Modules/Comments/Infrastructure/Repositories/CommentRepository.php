<?php

namespace App\Modules\Comments\Infrastructure\Repositories;

use App\Modules\Comments\Persistence\Interfaces\CommentRepositoryInterface;
use App\Modules\Comments\Persistence\ORM\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CommentRepository implements CommentRepositoryInterface
{
    public function create(array $data): Comment
    {
        return DB::transaction(function () use ($data) {
            return Comment::create($data);
        });
    }

    public function findById(int $id): ?Comment
    {
        return Comment::with(['author', 'article'])->find($id);
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getByArticleId(int $articleId): Collection
    {
        return Comment::with(['author'])
            ->where('article_id', $articleId)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function update(Comment $comment, array $data): Comment
    {
        return DB::transaction(function () use ($comment, $data) {
            $comment->update($data);

            return $comment->fresh();
        });
    }

    public function delete(Comment $comment): bool
    {
        return DB::transaction(function () use ($comment) {
            return $comment->delete();
        });
    }
}
