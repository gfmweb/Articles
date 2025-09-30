<?php

namespace App\Modules\Users\Application\Actions;

use App\Modules\Users\Persistence\Interfaces\UserRepositoryInterface;

readonly class DeleteUserAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    /**
     * @throws \Exception
     */
    public function execute(int $userId): bool
    {
        $user = $this->userRepository->findById($userId);

        if (! $user) {
            throw new \Exception(__('users::messages.not_found'));
        }

        return $this->userRepository->delete($user);
    }
}
