<?php

namespace App\Modules\Comments\Application\Queries;

use App\Modules\Comments\Domain\Exceptions\CommentNotFoundException;
use App\Modules\Comments\Persistence\Interfaces\CommentRepositoryInterface;
use App\Modules\Comments\Persistence\ORM\Comment;

readonly class GetCommentQuery
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository
    ) {}

    /**
     * @throws CommentNotFoundException
     */
    public function execute(int $id): Comment
    {
        $comment = $this->commentRepository->findById($id);

        if (! $comment) {
            throw new CommentNotFoundException;
        }

        return $comment;
    }
}
