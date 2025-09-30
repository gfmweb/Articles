<?php

namespace App\Modules\Users\Application\Actions;

use App\Modules\Users\Persistence\Interfaces\UserRepositoryInterface;
use App\Modules\Users\Persistence\ORM\User;

class DeleteUserAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    /**
     * Execute delete user action
     *
     * @throws \Exception
     */
    public function execute(int $userId): bool
    {
        // Find user
        $user = $this->userRepository->findById($userId);

        if (! $user) {
            throw new \Exception(__('users.delete.not_found'));
        }

        // Delete user
        return $this->userRepository->delete($user);
    }
}
