<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticateJwt
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return JsonResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        $link = config('app.url') . '/auth';
        $token = Cookie::get('jwt');

        if (!$token) {
            // Token is not present in the cookie
            return response()->json([
                'status' => false,
                'link' => $link,
                'message' => 'Unauthorized. Token not found.',
            ])->setStatusCode(401);
        }

        try {
            // Validate the token using the JWTAuth library
            $user = JWTAuth::setToken($token)->authenticate();

            if (!$user) {
                // Token is invalid
                return response()->json([
                    'status' => false,
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
                'status' => false,
                'message' => 'Unauthorized. Invalid token.',
            ])
                ->setStatusCode(401);
        }
    }
}
