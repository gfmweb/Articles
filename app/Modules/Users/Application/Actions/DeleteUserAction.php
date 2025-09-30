<?php

namespace App\Modules\Users\Application\Actions;

use App\Modules\Users\Domain\Exceptions\UserNotFoundException;
use App\Modules\Users\Persistence\Interfaces\UserRepositoryInterface;

readonly class DeleteUserAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * @throws UserNotFoundException
     */
    public function execute(int $userId): bool
    {
        $user = $this->userRepository->findById($userId);

        if (! $user) {
            throw new UserNotFoundException;
        }

        return $this->userRepository->delete($user);
    }
}
