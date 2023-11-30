<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\AuthInterface;
use App\Enums\AuthStrEnum;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Cookie;
use Laravel\Socialite\Facades\Socialite;

class SSOAuthController extends Controller implements AuthInterface
{
    /**
     * @const string
     * Source of admin redirect.
     */
    private const ADMIN_COOKIE_SOURCE = 'admin';

    /**
     * @constructor SSOAuthController
     * @param AuthService $authService
     */
    public function __construct(
        private readonly AuthService $authService,
    ) {}

    /**
     * Get the authenticated User info for SSO auth flow.
     *
     * @return JsonResponse
     */
    public function user(): JsonResponse
    {
        return self::sendSuccess(
            'Authenticated user',
            [
                'user' => auth()->guard('web')->user(),
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
        return Socialite::driver(AuthStrEnum::DRIVER->value)->redirect();
    }

    /**
     * Obtain the user information from Azure.
     *
     * @return \Illuminate\Contracts\Foundation\Application|Application|JsonResponse|RedirectResponse|Redirector
     */
    public function handleProviderCallback(
    ): Application|JsonResponse|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $driver = AuthStrEnum::DRIVER->value;
        $user = Socialite::driver($driver)->user();

        $existingUser = User::whereBlind(
            'email',
            'email_index',
            $user->getEmail(),
        )->first();

        $existingUser = $this->authService->handleUser($user, $existingUser);

        $this->authService->login($existingUser);

        $source = Cookie::get(
            config('app.env')
            . '_'
            . AuthStrEnum::SOURCE_COOKIE->value,
        );

        $adminUrl = config('app.admin_url');
        $frontendUrl = config('app.frontend_url');

        if (
            $source
            && str_contains($source, self::ADMIN_COOKIE_SOURCE)
        ) {
            return redirect($adminUrl);
        }

        return redirect($frontendUrl);
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
        );
    }
}
