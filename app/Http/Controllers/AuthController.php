<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Check if the user is authorized and return a link as needed.
     *
     * @return JsonResponse
     */
    public function checkAuthorization(): JsonResponse
    {
        $link = config('app.url');

        $jwt = Cookie::get('jwt');

        // Check if the user is authenticated and has a valid token
        if ($jwt) {
            // User is authorized, return a link and status as needed
            $response = [
                'status' => true,
                'token' => $jwt,
            ];
        } else {
            $response = [
                'status' => false,
                'link' => $link . 'auth',
            ];
        }

        return response()->json($response);
    }

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
     * @return \Illuminate\Contracts\Foundation\Application|Application|JsonResponse|RedirectResponse|Redirector
     */
    public function handleProviderCallback(): Application|JsonResponse|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
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

        // Create a JWT token from the user authenticated
        $token = JWTAuth::fromUser($existingUser);

        // Set the JWT token as a cookie on the response
        Cookie::queue('jwt', $token, 60 * 24, '/', 'dev-api-research.ffin.global');

        // Redirect to your frontend
        return redirect('http://localhost:5173');
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
