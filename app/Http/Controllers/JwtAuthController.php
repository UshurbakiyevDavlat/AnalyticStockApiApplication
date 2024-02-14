<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\JwtAuthInterface;
use App\Enums\StatusCodeEnum;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthController extends Controller implements JwtAuthInterface
{
    use ApiResponse;

    /** @inheritDoc */
    public function login(Request $request): JsonResponse
    {
        $user = User::whereBlind('email', 'email_index', $request->email)->first();

        if (!$user) {
            return self::sendError('User not found', null, StatusCodeEnum::NOT_FOUND->value);
        }

        $user->avatar_url = $user->avatar_url
            ? Storage::disk('admin')->url($user->avatar_url)
            : null;

        $token = JWTAuth::fromUser($user);
        auth()->login($user);

        return $this->createNewToken($token);
    }

    /** @inheritDoc */
    public function createNewToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL(),
            'user' => auth()->user(),
        ]);
    }

    /** @inheritDoc */
    public function userProfile(): Authenticatable
    {
        $user = auth()->user();
        $user->avatar_url = $user->avatar_url
            ? Storage::disk('admin')->url($user->avatar_url)
            : null;

        return $user;
    }
}
