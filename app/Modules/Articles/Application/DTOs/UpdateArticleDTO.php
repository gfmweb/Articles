<?php

namespace App\Modules\Articles\Application\DTOs;

class UpdateArticleDTO
{
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $content = null
    ) {
    }


    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'] ?? null,
            content: $data['content'] ?? null
        );
    }
}
