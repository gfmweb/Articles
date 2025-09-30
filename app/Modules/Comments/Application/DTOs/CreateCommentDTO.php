<?php

namespace App\Modules\Comments\Application\DTOs;

readonly class CreateCommentDTO
{
    public function __construct(
        public int $article_id,
        public int $author_id,
        public string $text
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            article_id: $data['article_id'],
            author_id: $data['author_id'],
            text: $data['text']
        );
    }
}
