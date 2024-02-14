<?php

namespace App\Contracts;

use App\Http\Requests\Post\ViewRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

interface ViewInterface
{
    /**
     * Get all views for the authenticated user.
     *
     * @OA\Get(
     *       path="/api/v1/posts/views",
     *       summary="Get viewed posts",
     *       description="Retrieve a list of posts viewed by the authenticated user.",
     *       operationId="getViews",
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
    public function getViews(): JsonResponse;

    /**
     * View post.
     *
     * @OA\Post(
     *        path="/api/v1/posts/views",
     *        summary="View post",
     *        description="View post with authenticated user.",
     *        operationId="viewPost",
     *        tags={"Posts"},
     *        security={{ "jwt": {} }},
     *
     *       @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="post_id", type="integer", example="1"),
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
     * @param ViewRequest $request The request object.
     * @return JsonResponse
     */
    public function viewPost(ViewRequest $request): JsonResponse;
}
