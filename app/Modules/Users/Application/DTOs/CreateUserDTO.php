<?php

namespace App\Modules\Users\Application\DTOs;

readonly class CreateUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }

    /**
     * Create from array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
        );
    }
}
