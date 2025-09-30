<?php

namespace App\Modules\Users\Presentation\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'auth.'.$request->ip();
        $maxAttempts = config('security.rate_limiting.auth_attempts', 5);
        $decayMinutes = config('security.rate_limiting.auth_decay_minutes', 1);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);

            return response()->json([
                'message' => __('auth.throttle', ['seconds' => $seconds]),
                'retry_after' => $seconds,
            ], 429);
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        return $next($request);
    }
}
