<?php

namespace App\Modules\Users\Application\Actions;

use App\Modules\Users\Application\DTOs\UpdateUserDTO;
use App\Modules\Users\Domain\Exceptions\UserAlreadyExistsException;
use App\Modules\Users\Domain\Exceptions\UserNotFoundException;
use App\Modules\Users\Persistence\Interfaces\UserRepositoryInterface;
use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Support\Facades\Hash;

readonly class UpdateUserAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * @throws UserNotFoundException
     * @throws UserAlreadyExistsException
     */
    public function execute(UpdateUserDTO $dto): User
    {
        $user = $this->userRepository->findById($dto->id);

        if (! $user) {
            throw new UserNotFoundException;
        }

        if ($dto->email && $dto->email !== $user->email) {
            $existingUser = $this->userRepository->findByEmail($dto->email);

            if ($existingUser) {
                throw new UserAlreadyExistsException($dto->email);
            }
        }
        $data = $dto->toArray();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->userRepository->update($user, $data);
    }
}
