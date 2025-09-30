<?php

namespace App\Modules\Comments\Application\DTOs;

class CreateCommentDTO
{
    public function __construct(
        public readonly int $article_id,
        public readonly int $author_id,
        public readonly string $text
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            article_id: $data['article_id'],
            author_id: $data['author_id'],
            text: $data['text']
        );
    }
}
