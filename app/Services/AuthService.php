<?php

namespace App\Services;

use App\Enums\AuthIntEnum;
use App\Enums\AuthStrEnum;
use App\Enums\EnvStrEnum;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    /**
     * @var string Domain
     */
    private string $domain;

    /**
     * @var string Name
     */
    private string $name;

    /**
     * @var string Path
     */
    private string $path;

    /**
     * @var int Expired time
     */
    private int $expired;

    /**
     * @var string SameSite
     */
    private string $sameSite;

    /**
     * @constructor AuthService
     */
    public function __construct()
    {
        $this->domain = config('app.env') !== EnvStrEnum::LOCAL_ENV->value
            ? AuthStrEnum::JWT_DOMAIN->value
            : '';
        $this->name = config('app.env')
            . '_'
            . AuthStrEnum::JWT_NAME->value;
        $this->path = AuthStrEnum::JWT_PATH->value;
        $this->expired = AuthIntEnum::EXPIRED->value;
        $this->sameSite = AuthStrEnum::SAME_SITE->value;
    }

    /**
     * Login the user and create a JWT token
     *
     * @param User $user User
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
            $this->sameSite,  // SameSite: 'None'
        );
    }

    /**
     * Create or update the user according to the Azure response and return it
     *
     * @param mixed $azureUser Azure user
     * @param User|null $user User
     * @return User
     */
    public function handleUser(mixed $azureUser, ?User $user): User
    {
        if ($user) {
            $user->update([
                'azure_token' => $azureUser->token,
            ]);
        } else {
            User::create([
                'name' => $azureUser->getName(),
                'email' => $azureUser->getEmail(),
                'azure_token' => $azureUser->token,
            ]);
        }

        return User::where(
            'email',
            $azureUser->getEmail(),
        )->first();
    }
}