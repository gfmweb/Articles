<?php

namespace Tests\Modules\Users\Unit;

use App\Modules\Users\Infrastructure\Repositories\UserRepository;
use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new UserRepository;
    }

    public function test_can_create_user(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ];

        $user = $this->repository->create($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
    }

    public function test_can_find_user_by_id(): void
    {
        $user = User::factory()->create();

        $foundUser = $this->repository->findById($user->id);

        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

    public function test_can_find_user_by_email(): void
    {
        $user = User::factory()->create();

        $foundUser = $this->repository->findByEmail($user->email);

        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->email, $foundUser->email);
    }

    public function test_can_update_user(): void
    {
        $user = User::factory()->create();
        $updateData = ['name' => 'Updated Name'];

        $updatedUser = $this->repository->update($user, $updateData);

        $this->assertEquals($updateData['name'], $updatedUser->name);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $updateData['name'],
        ]);
    }

    public function test_can_delete_user(): void
    {
        $user = User::factory()->create();

        $result = $this->repository->delete($user);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
