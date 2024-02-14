<?php

namespace App\Contracts;

use App\Http\Requests\Post\LikeRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

interface LikeInterface
{
    /**
     * Get user likes.
     *
     * @OA\Get(
     *       path="/api/v1/posts/likes",
     *       summary="Get liked posts",
     *       description="Retrieve a list of posts liked by the authenticated user.",
     *       operationId="getLikes",
     *       tags={"Posts"},
     *       security={{ "jwt": {} }},
     *
     *       @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *
     *           @OA\JsonContent(
     *               type="object",
     *
     *               @OA\Property(property="message", type="string", example="Success message"),
     *               @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PostResource")),
     *           ),
     *       ),
     *
     *       @OA\Response(response=400, description="Bad request"),
     *  )
     *
     * @return JsonResponse
     */
    public function getLikes(): JsonResponse;

    /**
     * Like or unlike a post.
     *
     * @OA\Post(
     *        path="/api/v1/posts/likes",
     *        summary="Like or unlike post",
     *        description="Like or unlike a post of authenticated user.",
     *        operationId="likePost",
     *        tags={"Posts"},
     *        security={{ "jwt": {} }},
     *
     *       @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="likeable_id", type="integer", example="1"),
     *         ),
     *     ),
     *
     *        @OA\Response(
     *            response=200,
     *            description="Successful operation",
     *
     *            @OA\JsonContent(
     *                type="object",
     *
     *                @OA\Property(property="message", type="string", example="Success message"),
     *            ),
     *        ),
     *
     *        @OA\Response(response=400, description="Bad request"),
     *   )
     *
     * @param LikeRequest $request Request
     * @return JsonResponse
     */
    public function likePost(LikeRequest $request): JsonResponse;
}
