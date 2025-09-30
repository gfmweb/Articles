<?php

namespace App\Modules\Users\Application\Actions;

use App\Modules\Users\Application\DTOs\UpdateUserDTO;
use App\Modules\Users\Persistence\Interfaces\UserRepositoryInterface;
use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Support\Facades\Hash;

readonly class UpdateUserAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    /**
     * @throws \Exception
     */
    public function execute(UpdateUserDTO $dto): User
    {
        $user = $this->userRepository->findById($dto->id);

        if (! $user) {
            throw new \Exception(__('users::messages.not_found'));
        }

        if ($dto->email && $dto->email !== $user->email) {
            $existingUser = $this->userRepository->findByEmail($dto->email);

            if ($existingUser) {
                throw new \Exception(__('users::messages.email_exists'));
            }
        }
        $data = $dto->toArray();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->userRepository->update($user, $data);
    }
}
