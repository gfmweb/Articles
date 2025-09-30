<?php

namespace App\Modules\Users\Application\Queries;

use App\Modules\Users\Persistence\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

readonly class GetUsersQuery
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function all(): Collection
    {
        return $this->userRepository->getAll();
    }

    public function paginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepository->getPaginated($perPage);
    }
}
