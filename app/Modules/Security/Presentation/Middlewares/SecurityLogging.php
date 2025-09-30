<?php

namespace App\Modules\Security\Presentation\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SecurityLogging
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->getStatusCode() >= 400) {
            $logData = [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => $response->getStatusCode(),
                'user_id' => $request->user()?->id,
                'timestamp' => now()->toISOString(),
            ];

            if ($response->getStatusCode() === 429) {
                Log::warning('Rate limit exceeded', $logData);
            } elseif ($response->getStatusCode() === 401) {
                Log::info('Unauthorized access attempt', $logData);
            } elseif ($response->getStatusCode() === 403) {
                Log::warning('Forbidden access attempt', $logData);
            } else {
                Log::warning('Security event', $logData);
            }
        }

        return $response;
    }
}
