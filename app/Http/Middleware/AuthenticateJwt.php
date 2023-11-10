<?php

namespace App\Http\Middleware;

use App\Enums\AuthStrEnum;
use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
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

        try {
            $this->authenticateUser($token);
        } catch (TokenExpiredException $e) {
            Log::error($e->getMessage());

            return $this->handleTokenExpired($token);
        } catch (JWTException $e) {
            return $this->handleJWTException($e, 'Token not found.', $link);
        }

        return $next($request);
    }

    /**
     * Handle JWTException
     *
     * @param $message
     * @param $link
     * @return JsonResponse
     */
    protected function handleUnauthorized($message, $link): JsonResponse
    {
        return self::sendSuccess(
            'Unauthorized. ' . $message,
            [
                'link' => $link,
            ],
        );
    }

    /**
     * Authenticate user
     *
     * @throws JWTException
     */
    protected function authenticateUser($token): JWTSubject
    {
        $user = JWTAuth::setToken($token)->authenticate();

        if (!$user) {
            throw new JWTException('Invalid token.');
        }

        return $user;
    }

    /**
     * Handle TokenExpiredException
     *
     * @param $token
     * @return JWTSubject|bool|JsonResponse
     */
    protected function handleTokenExpired($token): JWTSubject|bool|JsonResponse
    {
        $refreshedToken = JWTAuth::refresh($token);

        return JWTAuth::setToken($refreshedToken)->toUser();
    }

    /**
     * Handle JWTException
     *
     * @param JWTException $e
     * @param string $message
     * @param string $link
     * @return JsonResponse
     */
    protected function handleJWTException(
        JWTException $e,
        string $message,
        string $link,
    ): JsonResponse {
        Log::error(
            'Error while validating token: ' . $e->getMessage(),
            [
                'data' => $e->getTraceAsString(),
            ],
        );

        return $this->handleUnauthorized(
            $message,
            $link,
        );
    }
}
