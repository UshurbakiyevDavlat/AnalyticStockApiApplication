<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Posts;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class PostController extends Controller
{
    /**
     * Get all posts.
     *
     * @OA\Get(
     *        path="/api/v1/posts",
     *        summary="Get all posts",
     *        description="Retrieve a list of all posts.",
     *        operationId="getPosts",
     *        tags={"Posts"},
     *        security={{ "jwt": {} }},
     *        @OA\Response(
     *            response=200,
     *            description="Successful operation",
     *            @OA\JsonContent(
     *                type="object",
     *                @OA\Property(property="message", type="string", example="Success message"),
     *                @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PostResource")),
     *            ),
     *        ),
     *        @OA\Response(response=400, description="Bad request"),
     *   )
     *
     * @return JsonResponse
     */
    public function getPosts(): JsonResponse
    {
        return self::sendSuccess(
            __('response.success'),
            PostCollection::make(
                Post::orderBy('created_at', 'desc')->get(),
            )->jsonSerialize(),
        );
    }

    /**
     * Get post by id.
     *
     * @OA\Get(
     *       path="/api/v1/posts/{post}",
     *       summary="Get post by ID",
     *       description="Retrieve a specific post by ID.",
     *       operationId="getPost",
     *       tags={"Posts"},
     *       security={{ "jwt": {} }},
     *       @OA\Parameter(
     *           name="post",
     *           in="path",
     *           description="ID of the post",
     *           required=true,
     *           @OA\Schema(type="integer"),
     *       ),
     *       @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="message", type="string", example="Success message"),
     *               @OA\Property(property="data", type="object", ref="#/components/schemas/PostResource"),
     *           ),
     *       ),
     *       @OA\Response(response=400, description="Bad request"),
     *       @OA\Response(response=404, description="Post not found"),
     *  )
     *
     * @param Post $post
     * @return JsonResponse
     */
    public function getPost(Post $post): JsonResponse
    {
        return self::sendSuccess(
            __('response.success'),
            PostResource::make($post)->jsonSerialize(),
        );
    }
}
