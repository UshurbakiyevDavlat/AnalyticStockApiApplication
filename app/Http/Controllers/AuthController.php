<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Redirect the user to the Azure authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|RedirectResponse
     */
    public function redirectToProvider(): \Symfony\Component\HttpFoundation\RedirectResponse|RedirectResponse
    {
        return Socialite::driver('azure')->redirect();
    }

    /**
     * Obtain the user information from Azure.
     *
     * @return JsonResponse
     */
    public function handleProviderCallback(): JsonResponse
    {
        $user = Socialite::driver('azure')->user();
        Log::info('userInfoFromAzure', ['data' => $user]);

        // Find the user by their email in your application's users table
        $existingUser = User::where('email', $user->getEmail())->first();

        if (!$existingUser) {
            // Handle user not found error as needed
            return response()->json(
                [
                    'error' => 'User not found',
                ],
                404,
            );
        }

        $existingUser->azure_token = $user->token;
        $existingUser->save();

        // Authenticate the user in Laravel
        Auth::login($existingUser);

        // Generate a JWT token for the authenticated user
        $token = JWTAuth::fromUser($existingUser);

        return response()->json(
            [
                'token' => $token,
                'user' => $existingUser,
            ],
        );
    }

    /**
     *  Logout the user.
     *
     * @param Request $request
     * @return Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
     */
    public function logout(Request $request,
    ): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application {
        Auth::guard()->logout();
        $request->session()->flush();
        JWTAuth::invalidate(JWTAuth::getToken()); // Invalidate the JWT token
        $azureLogoutUrl = Socialite::driver('azure')->getLogoutUrl(route('login'));

        return redirect($azureLogoutUrl);
    }
}
