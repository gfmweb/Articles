<?php

namespace App\Modules\Articles\Application\DTOs;

readonly class UpdateArticleDTO
{
    public function __construct(
        public ?string $title = null,
        public ?string $content = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'] ?? null,
            content: $data['content'] ?? null
        );
    }
}
