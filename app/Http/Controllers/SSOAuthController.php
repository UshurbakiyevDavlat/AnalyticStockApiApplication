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
use OpenApi\Annotations as OA;

class SSOAuthController extends Controller implements AuthInterface
{
    use ApiResponse;

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

        $existingUser = User::where(
            'email',
            $user->getEmail(),
        )->first();

        $existingUser = $this->authService->handleUser($user, $existingUser);

        $this->authService->login($existingUser);

        $source = Cookie::get(AuthStrEnum::SOURCE_COOKIE->value);

        $adminUrl = config('app.admin_url');
        $frontendUrl = config('app.frontend_url');

        if (str_contains($source, 'admin')) {
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
