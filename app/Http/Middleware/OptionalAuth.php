<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class OptionalAuth
{
    /**
     * Handle an incoming request.
     * Tries to authenticate user from bearer token without requiring authentication.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Если пользователь уже аутентифицирован через middleware, пропускаем
        if ($request->user()) {
            return $next($request);
        }

        // Пытаемся аутентифицировать из токена
        if ($token = $request->bearerToken()) {
            try {
                $accessToken = PersonalAccessToken::findToken($token);

                if ($accessToken && property_exists($accessToken, 'tokenable') && $accessToken->tokenable) {
                    // Устанавливаем пользователя в запрос
                    $request->setUserResolver(fn () => $accessToken->tokenable);
                }
            } catch (Throwable $e) {
                // Логируем ошибку, но не прерываем выполнение
                Log::debug('Failed to authenticate user from token', [
                    'error' => $e->getMessage(),
                    'token_prefix' => substr($token, 0, 10).'...',
                ]);
            }
        }

        return $next($request);
    }
}
