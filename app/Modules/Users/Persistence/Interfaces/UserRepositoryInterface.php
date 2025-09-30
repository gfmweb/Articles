<?php

namespace App\Modules\Users\Persistence\Interfaces;

use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function getAll(): Collection;

    public function getPaginated(int $perPage = 15): LengthAwarePaginator;

    public function create(array $data): User;

    public function update(User $user, array $data): User;

    public function delete(User $user): bool;

    public function save(User $user): User;
}
