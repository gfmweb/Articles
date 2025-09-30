<?php

namespace App\Modules\Comments\Application\Actions;

use App\Modules\Comments\Persistence\Interfaces\CommentRepositoryInterface;
use App\Modules\Comments\Persistence\ORM\Comment;

class DeleteCommentAction
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository
    ) {}

    public function execute(Comment $comment): bool
    {
        return $this->commentRepository->delete($comment);
    }
}
