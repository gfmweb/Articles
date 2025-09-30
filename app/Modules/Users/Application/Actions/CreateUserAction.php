<?php

namespace App\Modules\Users\Application\Actions;

use App\Modules\Users\Application\DTOs\CreateUserDTO;
use App\Modules\Users\Domain\Exceptions\UserAlreadyExistsException;
use App\Modules\Users\Persistence\Interfaces\UserRepositoryInterface;
use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Support\Facades\Hash;

readonly class CreateUserAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * @throws UserAlreadyExistsException
     */
    public function execute(CreateUserDTO $dto): User
    {
        $existingUser = $this->userRepository->findByEmail($dto->email);

        if ($existingUser) {
            throw new UserAlreadyExistsException($dto->email);
        }

        $data = $dto->toArray();
        $data['password'] = Hash::make($data['password']);

        return $this->userRepository->create($data);
    }
}
