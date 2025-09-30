<?php

namespace App\Modules\Comments\Application\Actions;

use App\Modules\Comments\Application\DTOs\CreateCommentDTO;
use App\Modules\Comments\Persistence\Interfaces\CommentRepositoryInterface;
use App\Modules\Comments\Persistence\ORM\Comment;

readonly class CreateCommentAction
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository
    ) {
    }

    public function execute(CreateCommentDTO $dto): Comment
    {
        return $this->commentRepository->create([
            'article_id' => $dto->article_id,
            'author_id' => $dto->author_id,
            'text' => $dto->text,
        ]);
    }
}
