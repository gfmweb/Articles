<?php

namespace App\Modules\Comments\Application\Actions;

use App\Modules\Comments\Application\DTOs\UpdateCommentDTO;
use App\Modules\Comments\Persistence\Interfaces\CommentRepositoryInterface;
use App\Modules\Comments\Persistence\ORM\Comment;

readonly class UpdateCommentAction
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository
    ) {}

    public function execute(Comment $comment, UpdateCommentDTO $dto): Comment
    {
        return $this->commentRepository->update($comment, [
            'text' => $dto->text
        ]);
    }
}
