<?php

namespace App\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

interface JwtAuthInterface
{
    /**
     * Authenticate a user and return a JWT token.
     *
     * @OA\Post(
     *     path="/api/jwt",
     *     summary="Authenticate a user and return a JWT token",
     *     tags={"Authentication"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"email"},
     *
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful authentication",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="token", type="string", description="JWT token")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="error", type="string", description="Error message")
     *         )
     *     )
     * )
     */
    public function login(Request $request): JsonResponse;

    /**
     * Get the token array structure.
     *
     * @param string $token
     * @return JsonResponse
     */
    public function createNewToken(string $token): JsonResponse;

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
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *         ),
     *     ),
     *
     *     @OA\Response(response=401, description="Unauthorized"),
     * )
     *
     * @return Authenticatable
     */
    public function userProfile(): Authenticatable;
}
