<?php

namespace App\Modules\Users\Application\Queries;

use App\Modules\Users\Persistence\Interfaces\UserRepositoryInterface;
use App\Modules\Users\Persistence\ORM\User;

readonly class GetUserQuery
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    /**
     * Get user by ID
     */
    public function byId(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Get user by email
     */
    public function byEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }
}
