<?php

namespace App\Services;

use App\Enums\AuthIntEnum;
use App\Enums\AuthStrEnum;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Str;
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
            true,  // Secure
            true,   // HttpOnly
            'None',  // SameSite: 'None'
        );
    }

    /**
     * Create or update the user according to the Azure response and return it
     *
     * @param mixed $azureUser
     * @param User|null $user
     * @return User
     */
    public function handleUser(mixed $azureUser, ?User $user): User
    {
        if (!$user) {
            User::create([
                'name' => $azureUser->getName(),
                'email' => $azureUser->getEmail(),
                'password' => Hash::make(Str::random(6)),
                'azure_token' => $azureUser->token,
            ]);
        } else {
            $user->update([
                'azure_token' => $azureUser->token,
            ]);
        }

        return User::where(
            'email',
            $azureUser->getEmail(),
        )->first();
    }
}