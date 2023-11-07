<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirectToProvider(): \Symfony\Component\HttpFoundation\RedirectResponse|RedirectResponse
    {
        return Socialite::driver('azure')->redirect();
    }

    public function handleProviderCallback(
    ): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $user = Socialite::driver('azure')->user();
        Log::info('userInfoFromAzure', ['data' => $user]);

        return redirect('/home');
    }

    public function logout(Request $request,
    ): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application {
        Auth::guard()->logout();
        $request->session()->flush();
        $azureLogoutUrl = Socialite::driver('azure')->getLogoutUrl(route('login'));

        return redirect($azureLogoutUrl);
    }
}
