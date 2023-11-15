<?php

namespace App\Http\Controllers;

use App\Contracts\AuthInterface;
use App\Enums\AuthStrEnum;
use App\Enums\StatusCodeEnum;
use App\Models\User;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Cookie;
use Laravel\Socialite\Facades\Socialite;
use Log;

class AuthController extends Controller implements AuthInterface
{
    use ApiResponse;

    /**
     * @constructor AuthController
     * @param AuthService $authService
     */
    public function __construct(
        private readonly AuthService $authService,
    ) {}

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function user(): JsonResponse
    {
        return self::sendSuccess(
            'Authenticated user',
            [
                'user' => auth()->user(),
                'status' => true,
            ],
        );
    }

    /**
     * Redirect the user to the Azure authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|RedirectResponse
     */
    public function redirectToProvider(): \Symfony\Component\HttpFoundation\RedirectResponse|RedirectResponse
    {
        \Illuminate\Support\Facades\Log::info('cookie in provider', [
            'sourceCOokie' => Cookie::get('source'),
            'allCookies' => $_COOKIE,
        ]);

        return Socialite::driver('azure')->redirect();
    }

    /**
     * Obtain the user information from Azure.
     *
     * @return \Illuminate\Contracts\Foundation\Application|Application|JsonResponse|RedirectResponse|Redirector
     */
    public function handleProviderCallback(
    ): Application|JsonResponse|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        \Illuminate\Support\Facades\Log::info(
            'cookie in callback',
            [
                'sourceCOokie' => Cookie::get('source'),
                'allCookies' => $_COOKIE,
            ],
        );
        $driver = AuthStrEnum::DRIVER->value;
        $user = Socialite::driver($driver)->user();

        $existingUser = User::where(
            'email',
            $user->getEmail(),
        )->first();

        if (!$existingUser) {
            return self::sendError(
                'User not found',
                [],
                StatusCodeEnum::NOT_FOUND->value,
            );
        }

        $existingUser->update(
            [
                'azure_token' => $user->token,
            ],
        );

        $this->authService->login($existingUser);

        $source = Cookie::get('source');
        Log::info('Source: ' . $source);

        if (!str_contains($source, 'admin')) {
            return redirect(config('app.frontend_url'));
        } else {
            return redirect(config('app.admin_url') . '/user');
        }
    }

    // TODO Implement logout for sso and jwt

    /**
     * Log the user out of the application.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        return self::sendSuccess(
            'Logout',
            [],
        );
    }
}
