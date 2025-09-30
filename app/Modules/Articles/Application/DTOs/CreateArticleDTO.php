<?php

namespace App\Modules\Articles\Application\DTOs;

class CreateArticleDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $content,
        public readonly int $author_id
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            content: $data['content'],
            author_id: $data['author_id']
        );
    }
}
