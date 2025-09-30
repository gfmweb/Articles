<?php

namespace Tests\Modules\Users\Unit;

use App\Modules\Users\Application\Actions\RegisterUserAction;
use App\Modules\Users\Application\DTOs\RegisterUserDTO;
use App\Modules\Users\Infrastructure\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterUserActionTest extends TestCase
{
    use RefreshDatabase;

    private RegisterUserAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new RegisterUserAction(new UserRepository);
    }

    public function test_can_register_user(): void
    {
        $dto = new RegisterUserDTO(
            name: 'Test User',
            email: 'test@example.com',
            password: 'password123'
        );

        $user = $this->action->execute($dto);

        $this->assertEquals($dto->name, $user->name);
        $this->assertEquals($dto->email, $user->email);
        $this->assertTrue(Hash::check($dto->password, $user->password));
        $this->assertDatabaseHas('users', [
            'name' => $dto->name,
            'email' => $dto->email,
        ]);
    }
}
