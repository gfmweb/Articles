<?php

namespace App\Modules\Users\Tests\Unit;

use App\Modules\Users\Application\Actions\CreateUserAction;
use App\Modules\Users\Application\DTOs\CreateUserDTO;
use App\Modules\Users\Infrastructure\Repositories\UserRepository;
use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateUserActionTest extends TestCase
{
    use RefreshDatabase;

    private CreateUserAction $action;

    private UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new UserRepository;
        $this->action = new CreateUserAction($this->repository);
    }

    public function test_can_create_user(): void
    {
        $dto = new CreateUserDTO(
            name: 'John Doe',
            email: 'john@example.com',
            password: 'password123'
        );

        $user = $this->action->execute($dto);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($dto->name, $user->name);
        $this->assertEquals($dto->email, $user->email);
        $this->assertTrue(Hash::check($dto->password, $user->password));
    }

    public function test_throws_exception_when_email_exists(): void
    {
        User::factory()->create(['email' => 'john@example.com']);

        $dto = new CreateUserDTO(
            name: 'John Doe',
            email: 'john@example.com',
            password: 'password123'
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(__('users.create.email_exists'));

        $this->action->execute($dto);
    }
}
