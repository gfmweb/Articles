<?php

namespace Tests\Modules\Users\Unit;

use App\Modules\Users\Application\Actions\LoginUserAction;
use App\Modules\Users\Application\DTOs\LoginUserDTO;
use App\Modules\Users\Infrastructure\Repositories\UserRepository;
use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginUserActionTest extends TestCase
{
    use RefreshDatabase;

    private LoginUserAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new LoginUserAction(new UserRepository);
    }

    public function test_can_login_user_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $dto = new LoginUserDTO(
            email: 'test@example.com',
            password: 'password123'
        );

        $result = $this->action->execute($dto);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($user->id, $result->id);
    }

    public function test_returns_null_with_invalid_email(): void
    {
        $dto = new LoginUserDTO(
            email: 'nonexistent@example.com',
            password: 'password123'
        );

        $result = $this->action->execute($dto);

        $this->assertNull($result);
    }

    public function test_returns_null_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $dto = new LoginUserDTO(
            email: 'test@example.com',
            password: 'wrongpassword'
        );

        $result = $this->action->execute($dto);

        $this->assertNull($result);
    }
}
