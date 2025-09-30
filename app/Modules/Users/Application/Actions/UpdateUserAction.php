<?php

namespace App\Modules\Users\Application\Actions;

use App\Modules\Users\Application\DTOs\UpdateUserDTO;
use App\Modules\Users\Persistence\Interfaces\UserRepositoryInterface;
use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    /**
     * Execute update user action
     *
     * @throws \Exception
     */
    public function execute(UpdateUserDTO $dto): User
    {
        // Find user
        $user = $this->userRepository->findById($dto->id);

        if (! $user) {
            throw new \Exception(__('users::messages.not_found'));
        }

        // Check if email is being changed and if it already exists
        if ($dto->email && $dto->email !== $user->email) {
            $existingUser = $this->userRepository->findByEmail($dto->email);

            if ($existingUser) {
                throw new \Exception(__('users::messages.email_exists'));
            }
        }

        // Prepare data for update
        $data = $dto->toArray();

        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Update user
        return $this->userRepository->update($user, $data);
    }
}
