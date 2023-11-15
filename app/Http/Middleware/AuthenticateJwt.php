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
    public function handle($request, Closure $next): mixed
    {
        // Get the entire request URL
        $referrer = $request->headers->get('Referer');
        $authHeader = $request->headers->get('Authorization');
        $token = $authHeader
            ?: Cookie::get(AuthStrEnum::JWT_NAME->value);
        $link = config('app.url') . '/auth';

        if (!$token) {
            return $this->handleUnauthorized(
                'Token not found',
                $link,
            );
        }

        try {
            $this->authenticateUser($token);
        } catch (TokenExpiredException $e) {
            Log::error($e->getMessage());
            try {
                $this->handleTokenExpired($token);
            } catch (JWTException $e) {
                return $this->handleJwtError(
                    $e->getMessage(),
                    $e->getTraceAsString(),
                );
            }
        } catch (JWTException $e) {
            return $this->handleJWTException(
                $e,
                'Something wrong with the token',
            );
        }

        return $next($request)->cookie(
            AuthStrEnum::SOURCE_COOKIE->value,
            $referrer,
        );
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
                'status' => false,
            ],
        );
    }

    /**
     * Handle JWTException
     *
     * @param string $message
     * @param string $trace
     * @return JsonResponse
     */
    protected function handleJwtError(string $message, string $trace): JsonResponse
    {
        return self::sendError(
            'JwtError. ' . $message,
            [
                'trace' => $trace,
                'status' => false,
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
     * @return JsonResponse
     */
    protected function handleJWTException(
        JWTException $e,
        string $message,
    ): JsonResponse {
        Log::error(
            'Error while validating token: ' . $e->getMessage(),
            [
                'data' => [
                    $e->getTraceAsString(),
                ],
            ],
        );

        return $this->handleJwtError(
            $message,
            $e->getTraceAsString(),
        );
    }
}
