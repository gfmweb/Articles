<?php

namespace App\Modules\Users\Presentation\Controllers;

use App\Modules\Users\Application\Actions\LoginUserAction;
use App\Modules\Users\Application\Actions\RegisterUserAction;
use App\Modules\Users\Application\DTOs\LoginUserDTO;
use App\Modules\Users\Application\DTOs\RegisterUserDTO;
use App\Modules\Users\Presentation\Requests\LoginRequest;
use App\Modules\Users\Presentation\Requests\RegisterRequest;
use App\Modules\Users\Presentation\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

readonly class AuthController
{
    public function __construct(
        private RegisterUserAction $registerUserAction,
        private LoginUserAction $loginUserAction
    ) {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $dto = RegisterUserDTO::fromArray($request->validated());
        $user = $this->registerUserAction->execute($dto);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'data' => [
                'user' => new UserResource($user),
                'token' => $token,
            ],
            'message' => __('users::messages.registered'),
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $dto = LoginUserDTO::fromArray($request->validated());
        $user = $this->loginUserAction->execute($dto);

        if (! $user) {
            return response()->json([
                'message' => __('users::messages.login_failed'),
            ], 401);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'data' => [
                'user' => new UserResource($user),
                'token' => $token,
            ],
            'message' => __('users::messages.logged_in'),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => __('users::messages.logged_out'),
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'data' => new UserResource($request->user()),
        ]);
    }
}
