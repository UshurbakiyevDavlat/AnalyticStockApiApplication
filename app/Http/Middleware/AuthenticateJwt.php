<?php

namespace App\Http\Middleware;

use App\Enums\AuthStrEnum;
use App\Enums\StatusCodeEnum;
use App\Models\User;
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
        $authHeader = $request->headers->get('Authorization');
        $token = $this->getToken($authHeader);
        $link = config('app.url') . '/auth';

        if (
            (
                config('app.debug')
                && $request->origin == config('app.url'))
            || config('app.env') == 'local'
        ) {
            $user = JWTAuth::setToken($token)->toUser();

            if ($user) {
                // Set the authenticated user
                auth()->setUser(User::find($user->getJWTIdentifier()));
            } else {
                // Handle the case where the user cannot be identified
                // You might want to log an error, return an unauthorized response, or take other appropriate action.
                // For example:
                return response()->json(['error' => 'Unauthorized'], StatusCodeEnum::UNAUTHORIZED->value);
            }

            if (
                !$token
                || !auth()->user()
            ) {
                return response()->json(
                    [
                        'error' => 'Unauthorized',
                    ],
                    StatusCodeEnum::UNAUTHORIZED->value,
                );
            }

            return $next($request);
        }

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
                $this->handleTokenExpired();
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
     * @throws JWTException
     * @return void
     */
    protected function handleTokenExpired(): void
    {
        try {
            $refreshedToken = JWTAuth::refresh();
        } catch (\Exception $e) {
            Log::error('Token Refresh Error: ' . $e->getMessage());
            Log::error('Token Refresh Stack Trace: ' . $e->getTraceAsString());
            throw new JWTException('Something went wrong while refreshing token');
        }

        try {
            JWTAuth::setToken($refreshedToken)->toUser();
        } catch (\Exception $e) {
            Log::error('Token Set Error: ' . $e->getMessage());
            Log::error('Token Set Stack Trace: ' . $e->getTraceAsString());
            throw new JWTException('Something went wrong while setting token');
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

    /**
     * Get token from header
     *
     * @param string|null $authHeader
     * @return array|string|null
     */
    private function getToken(?string $authHeader): null|array|string
    {
        return $authHeader
            ?: Cookie::get(
                config('app.env')
                . '_'
                . AuthStrEnum::JWT_NAME->value,
            );
    }
}
