<?php

namespace App\Modules\Comments\Application\DTOs;

readonly class UpdateCommentDTO
{
    public function __construct(
        public string $text
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            text: $data['text']
        );
    }
}
