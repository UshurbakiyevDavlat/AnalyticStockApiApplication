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
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
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
     */
    public function __construct(
        private readonly AuthService $authService,
    ) {}

    /** @inheritDoc */
    public function user(): JsonResponse
    {
        $user = auth()->user();

        if ($user instanceof User) {
            $user->avatar_url = $user->avatar_url
                ? Storage::disk('admin')->url($user->avatar_url)
                : null;
        }

        return self::sendSuccess(
            'Authenticated user',
            [
                'user' => $user,
                'status' => true,
            ],
        );
    }

    /** @inheritDoc */
    public function redirectToProvider(): \Symfony\Component\HttpFoundation\RedirectResponse|RedirectResponse
    {
        return Socialite::driver(AuthStrEnum::DRIVER->value)->redirect();
    }

    /** @inheritDoc */
    public function handleProviderCallback(
    ): Application|JsonResponse|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application {
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

    /** @inheritDoc */
    public function logout(Request $request): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $azureLogoutUrl = Socialite::driver(AuthStrEnum::DRIVER->value)->getLogoutUrl(route('login'));
        return redirect($azureLogoutUrl);
    }
}
