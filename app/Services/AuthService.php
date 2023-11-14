<?php

namespace App\Services;

use App\Enums\AuthIntEnum;
use App\Enums\AuthStrEnum;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    /**
     * @var string
     */
    private string $domain;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $path;

    /**
     * @var int
     */
    private int $expired;

    /**
     * @constructor AuthService
     */
    public function __construct()
    {
        $this->domain = config('app.env') !== 'local'
            ? AuthStrEnum::JWT_DOMAIN->value
            : '';
        $this->name = AuthStrEnum::JWT_NAME->value;
        $this->path = AuthStrEnum::JWT_PATH->value;
        $this->expired = AuthIntEnum::EXPIRED->value;
    }

    /**
     * Login the user and create a JWT token
     *
     * @param User $user
     * @return void
     */
    public function login(User $user): void
    {
        Auth::login($user);

        // Create a JWT token from the user authenticated
        $token = JWTAuth::fromUser($user);

        // Set the JWT token as a cookie on the response
        Cookie::queue(
            $this->name,
            $token,
            $this->expired,
            $this->path,
            $this->domain,
            true,  // HttpOnly
            true,   // Secure
            'None'  // SameSite: 'None'
        );
    }
}