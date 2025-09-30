<?php

namespace App\Modules\Comments\Application\DTOs;

class UpdateCommentDTO
{
    public function __construct(
        public readonly ?string $text = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            text: $data['text'] ?? null
        );
    }
}
