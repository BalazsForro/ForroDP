<?php

namespace App\Http\Middleware;

use App\Models\DeviceToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class DeviceTokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken()
            ?: $request->header('X-Device-Token');

        if (!$token) {
            return response()->json([
                'message' => 'Missing device token'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Since token IS the hash already:
        $deviceToken = DeviceToken::where('token_hash', DeviceToken::hashToken($token))->first();

        if (!$deviceToken) {
            return response()->json([
                'message' => 'Invalid device token'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $key = 'device_token:' . $deviceToken->id;
        $limit = $deviceToken->rate_limit;

        if (RateLimiter::tooManyAttempts($key, $limit)) {
            $retryAfter = RateLimiter::availableIn($key);

            return response()->json([
                'message' => 'Too many requests',
                'retry_after' => $retryAfter,
            ], Response::HTTP_TOO_MANY_REQUESTS)
                ->header('Retry-After', $retryAfter)
                ->header('X-RateLimit-Limit', $limit)
                ->header('X-RateLimit-Remaining', 0);
        }

        RateLimiter::hit($key, 60);

        $deviceToken->update([
            'last_used_at' => now()
        ]);

        $request->attributes->set('deviceToken', $deviceToken);

        return $next($request)
            ->header('X-RateLimit-Limit', $limit)
            ->header('X-RateLimit-Remaining', RateLimiter::remaining($key, $limit));
    }
}
