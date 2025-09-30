<?php

namespace App\Modules\Users\Application\Actions;

use App\Modules\Users\Application\DTOs\CreateUserDTO;
use App\Modules\Users\Persistence\Interfaces\UserRepositoryInterface;
use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    /**
     * Execute create user action
     */
    public function execute(CreateUserDTO $dto): User
    {
        // Check if user with email already exists
        $existingUser = $this->userRepository->findByEmail($dto->email);

        if ($existingUser) {
            throw new \Exception(__('users.create.email_exists'));
        }

        // Hash password
        $data = $dto->toArray();
        $data['password'] = Hash::make($data['password']);

        // Create user
        return $this->userRepository->create($data);
    }
}
