<?php

namespace App\Contracts;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

interface AuthInterface
{
    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function user(): JsonResponse;

    /**
     * Redirect the user to the Azure authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|RedirectResponse
     */
    public function redirectToProvider(): \Symfony\Component\HttpFoundation\RedirectResponse|RedirectResponse;

    /**
     * Obtain the user information from Azure.
     *
     * @return JsonResponse|Redirector|RedirectResponse|Application
     */
    public function handleProviderCallback(): JsonResponse|Redirector|RedirectResponse|Application;

    /**
     * Log the user out of the application.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse;
}