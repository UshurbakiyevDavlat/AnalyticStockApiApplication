<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\LikeRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class LikeController extends Controller
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
     */
    public function getLikes(): JsonResponse
    {
        $user = auth()->user();

        return self::sendSuccess(
            __('response.success'),
            $user->likes->pluck('id')->toArray(),
        );
    }

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
     */
    public function likePost(LikeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = auth()->user();

        $like = $user->likes()
            ->where(
                'likeable_id',
                $data['likeable_id'],
            )
            ->first();

        if ($like) {
            $user->likes()->detach($data['likeable_id']);
            $liked = 'unliked';
        } else {
            $user->likes()->attach(
                $data['likeable_id'],
                ['likeable_type' => Post::class],
            );

            $liked = 'liked';
        }

        return self::sendSuccess(
            __('response.post.'.$liked),
        );
    }
}
