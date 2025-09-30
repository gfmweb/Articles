<?php

namespace App\Modules\Articles\Application\DTOs;

readonly class CreateArticleDTO
{
    public function __construct(
        public string $title,
        public string $content,
        public int $author_id
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
