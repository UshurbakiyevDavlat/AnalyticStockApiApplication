<?php

namespace App\Services;

use App\Enums\AuthIntEnum;
use App\Enums\AuthStrEnum;
use App\Enums\EnvStrEnum;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
        auth()->login($user);

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
        // $azureInfo = $this->getAzureInfo($azureUser->token, $azureUser->user['userPrincipalName']);

        if ($user) {
            $user->update([
                'azure_token' => $azureUser->token,
                'job_title' => $azureUser?->user?->jobTitle ?? null,
            ]);
        } else {
            $user = User::create([
                'name' => $azureUser->getName(),
                'email' => $azureUser->getEmail(),
                'azure_token' => $azureUser->token,
                'job_title' => $azureUser?->user?->jobTitle ?? null,
            ]);
        }

        Log::info('User authenticated', [
            'user' => $user,
            'azure_user' => $azureUser,
        ]);
        $user->avatar_url = Storage::disk('admin')->url($user->avatar_url);

        return $user;
    }

    /**
     * Get the Azure user info
     *
     * @param string $token
     * @param string $fio
     * @return array
     */
    private function getAzureInfo(string $token, string $fio): array
    {
        $userInfoUrl = config('app.graph_api') . '/users/' . $fio;
        $photoInfo = $userInfoUrl . '/photo/$value';

        // $userInfo = Http::withHeader('Authorization', $token)
        //     ->get($userInfoUrl)
        //     ->json();

        $photo = Http::withHeader('Authorization', $token)
            ->get($photoInfo)
            ->json();

        return [
            //'userInfo' => $userInfo,
            'photo' => $photo,
        ];
    }
}