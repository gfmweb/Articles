<?php

namespace App\Modules\Users\Infrastructure\Repositories;

use App\Modules\Users\Persistence\Interfaces\UserRepositoryInterface;
use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function getAll(): Collection
    {
        return User::all();
    }

    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return User::paginate($perPage);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user->fresh();
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function save(User $user): User
    {
        $user->save();

        return $user->fresh();
    }
}
