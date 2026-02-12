<?php

namespace App\Http\Middleware;

use App\Models\DeviceToken;
use Closure;
use Illuminate\Http\Request;
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

        $deviceToken->update([
            'last_used_at' => now()
        ]);

        $request->attributes->set('deviceToken', $deviceToken);

        return $next($request);
    }
}
