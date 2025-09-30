<?php

namespace App\Modules\Users\Presentation\Controllers;

use App\Modules\Users\Application\Actions\LoginUserAction;
use App\Modules\Users\Application\Actions\RegisterUserAction;
use App\Modules\Users\Application\DTOs\LoginUserDTO;
use App\Modules\Users\Application\DTOs\RegisterUserDTO;
use App\Modules\Users\Presentation\Requests\LoginRequest;
use App\Modules\Users\Presentation\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController
{
    public function __construct(
        private RegisterUserAction $registerUserAction,
        private LoginUserAction $loginUserAction
    ) {}

    /**
     * Register a new user.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $dto = RegisterUserDTO::fromArray($request->validated());
        $user = $this->registerUserAction->execute($dto);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
            'message' => __('users.messages.registered'),
        ], 201);
    }

    /**
     * Login user.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $dto = LoginUserDTO::fromArray($request->validated());
        $user = $this->loginUserAction->execute($dto);

        if (! $user) {
            return response()->json([
                'message' => __('users.messages.login_failed'),
            ], 401);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
            'message' => __('users.messages.logged_in'),
        ]);
    }

    /**
     * Logout user.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => __('users.messages.logged_out'),
        ]);
    }

    /**
     * Get current user.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $request->user(),
        ]);
    }
}
