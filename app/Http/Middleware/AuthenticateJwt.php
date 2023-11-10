<?php

namespace App\Http\Middleware;

use App\Enums\AuthStrEnum;
use App\Enums\StatusCodeEnum;
use App\Traits\ApiResponse;
use Closure;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticateJwt
{
    use ApiResponse;

    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return JsonResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        $token = Cookie::get(AuthStrEnum::JWT_NAME->value);
        $link = config('app.url') . '/auth';

        if (!$token) {
            return self::sendSuccess(
                'Unauthorized. Token not found.',
                [
                    'link' => $link,
                ],
            );
        }

        try {
            $user = JWTAuth::setToken($token)->authenticate();

            if (!$user) {
                return self::sendSuccess(
                    'Unauthorized. Invalid token.',
                    [
                        'link' => $link,
                    ],
                );
            }

            return $next($request);
        } catch (Exception $e) {
            Log::error(
                'Error while validating token: '
                . $e->getMessage(),
                [
                    'data' => $e->getTraceAsString(),
                ],
            );

            return self::sendError(
                'Unauthorized. Invalid token.',
                [],
                StatusCodeEnum::UNAUTHORIZED->value,
            );
        }
    }
}
