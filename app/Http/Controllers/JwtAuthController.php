<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Payload;

class JwtAuthController extends Controller
{
    /**
     * Authenticate a user and return a JWT token.
     *
     * @OA\Post(
     *     path="/api/jwt",
     *     summary="Authenticate a user and return a JWT token",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful authentication",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", description="JWT token")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", description="Error message")
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $user = User::whereBlind('email', 'email_index', $request->email)->first();

        $user->avatar_url = $user->avatar_url
            ? Storage::disk('admin')->url($user->avatar_url)
            : null;
        $token = JWTAuth::fromUser($user);
        auth()->login($user);

        return $this->createNewToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param string|Payload $token
     *
     * @return JsonResponse
     */
    protected function createNewToken(string|Payload $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ]);
    }

    /**
     * Get the authenticated user.
     *
     * @OA\Get(
     *     path="/api/profile",
     *     summary="Get authenticated user profile",
     *     description="Retrieve information about the authenticated user.",
     *     operationId="getUser",
     *     tags={"Authentication"},
     *     security={{"jwt": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *         ),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     * )
     *
     * @return Authenticatable
     */
    public function userProfile(): Authenticatable
    {
        $user = auth()->user();
        $user->avatar_url = $user->avatar_url
            ? Storage::disk('admin')->url($user->avatar_url)
            : null;

        return $user;
    }
}
