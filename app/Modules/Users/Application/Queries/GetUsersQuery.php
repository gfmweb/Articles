<?php

namespace App\Modules\Users\Application\Queries;

use App\Modules\Users\Persistence\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class GetUsersQuery
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    /**
     * Get all users
     */
    public function all(): Collection
    {
        return $this->userRepository->getAll();
    }

    /**
     * Get paginated users
     */
    public function paginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepository->getPaginated($perPage);
    }
}
