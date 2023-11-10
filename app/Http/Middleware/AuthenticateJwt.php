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
            try {
                $this->handleTokenExpired($token);
            } catch (JWTException $e) {
                return $this->handleJWTException(
                    $e,
                    'Token expired, but not refreshed',
                    $link,
                );
            }
        } catch (JWTException $e) {
            return $this->handleJWTException(
                $e,
                'Token not found',
                $link,
            );
        }

        return $next($request);
    }

    /**
     * Handle JWTException
     *
     * @param string $message
     * @param string $link
     * @return JsonResponse
     */
    protected function handleUnauthorized(string $message, string $link): JsonResponse
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
    protected function authenticateUser(string $token): JWTSubject
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
     * @param string $token
     * @throws JWTException
     * @return void
     */
    protected function handleTokenExpired(string $token): void
    {
        try {
            $refreshedToken = JWTAuth::refresh($token);
            JWTAuth::setToken($refreshedToken)->toUser();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new JWTException('Something went wrong while refreshing token');
        }
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
