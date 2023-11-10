<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticateJwt
{
    public function handle($request, Closure $next)
    {
        $link = config('app.url') . '/auth';
        $token = Cookie::get('jwt');

        if (!$token) {
            // Token is not present in the cookie
            return response()->json([
                'success' => false,
                'link' => $link,
                'message' => 'Unauthorized. Token not found.',
            ])
                ->setStatusCode(401);
        }

        try {
            // Validate the token using the JWTAuth library
            $user = JWTAuth::setToken($token)->authenticate();

            if (!$user) {
                // Token is invalid
                return response()->json([
                    'success' => false,
                    'link' => $link,
                    'message' => 'Unauthorized. Invalid token.',
                ])
                    ->setStatusCode(401);
            }

            // Token is valid, you can proceed
            return $next($request);
        } catch (Exception $e) {
            Log::error(
                'Error while validating token: '
                . $e->getMessage(),
                [
                    'data' => $e->getTraceAsString(),
                ],
            );

            // Token is invalid
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid token.',
            ])
                ->setStatusCode(401);
        }
    }
}
