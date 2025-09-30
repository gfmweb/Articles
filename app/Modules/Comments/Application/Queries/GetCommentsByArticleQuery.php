<?php

namespace App\Modules\Comments\Application\Queries;

use App\Modules\Comments\Persistence\Interfaces\CommentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GetCommentsByArticleQuery
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository
    ) {}

    public function execute(int $articleId): Collection
    {
        return $this->commentRepository->getByArticleId($articleId);
    }
}
