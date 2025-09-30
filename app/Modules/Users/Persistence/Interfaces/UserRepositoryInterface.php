<?php

namespace App\Modules\Users\Persistence\Interfaces;

use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    /**
     * Find user by ID
     */
    public function findById(int $id): ?User;

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User;

    /**
     * Get all users
     */
    public function getAll(): Collection;

    /**
     * Get paginated users
     */
    public function getPaginated(int $perPage = 15): LengthAwarePaginator;

    /**
     * Create new user
     */
    public function create(array $data): User;

    /**
     * Update user
     */
    public function update(User $user, array $data): User;

    /**
     * Delete user
     */
    public function delete(User $user): bool;

    /**
     * Save user
     */
    public function save(User $user): User;
}
