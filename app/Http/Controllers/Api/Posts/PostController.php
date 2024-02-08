<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Posts;

use App\Enums\CacheIntEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\GetPostsRequest;
use App\Http\Requests\Post\SearchRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use OpenApi\Annotations as OA;

class PostController extends Controller
{
    public function __construct(private readonly PostService $postService) {}

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
     *
     *     @OA\Parameter(
     *           name="Lang",
     *           in="header",
     *           description="Language for the response",
     *           required=false,
     *
     *           @OA\Schema(type="string", default="en"),
     *  ),
     *
     *        @OA\Response(
     *            response=200,
     *            description="Successful operation",
     *
     *            @OA\JsonContent(
     *                type="object",
     *
     *                @OA\Property(property="message", type="string", example="Success message"),
     *                @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PostResource")),
     *            ),
     *        ),
     *
     *        @OA\Response(response=400, description="Bad request"),
     *   )
     *
     * @param  GetPostsRequest  $request  Request object
     */
    public function getPosts(GetPostsRequest $request): JsonResponse
    {
        $data = $request->validated();
        $cacheKey = 'posts_list';
        $posts = $this->postService->getPosts($data);

        // $cachedPosts = Cache::remember(
        //     $cacheKey,
        //     now()->addMinutes(CacheIntEnum::EXPIRED->value),
        //     static function () use ($posts) {
        //         return PostCollection::make($posts, ['paginated' => true])->jsonSerialize();
        //     },
        // );

        return self::sendSuccess(__('response.success'), PostCollection::make($posts, ['paginated' => true])->jsonSerialize());
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
     *
     *       @OA\Parameter(
     *           name="Lang",
     *           in="header",
     *           description="Language for the response",
     *           required=false,
     *
     *           @OA\Schema(type="string", default="en"),
     *  ),
     *
     *       @OA\Parameter(
     *           name="post",
     *           in="path",
     *           description="ID of the post",
     *           required=true,
     *
     *           @OA\Schema(type="integer"),
     *       ),
     *
     *       @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *
     *           @OA\JsonContent(
     *               type="object",
     *
     *               @OA\Property(property="message", type="string", example="Success message"),
     *               @OA\Property(property="data", type="object", ref="#/components/schemas/PostResource"),
     *           ),
     *       ),
     *
     *       @OA\Response(response=400, description="Bad request"),
     *       @OA\Response(response=404, description="Post not found"),
     *  )
     */
    public function getPost(Post $post): JsonResponse
    {
        $cacheKey = 'post_' . $post->id;

        $cachedPost = Cache::remember(
            $cacheKey,
            now()->addMinutes(CacheIntEnum::EXPIRED->value),
            static function () use ($post) {
                return PostResource::make($post)->jsonSerialize();
            },
        );

        return self::sendSuccess(__('response.success'), $cachedPost);
    }

    /**
     * Search posts by query. It can be by title or Ticker/ISIN.
     *
     * @OA\Get(
     *     path="/api/v1/posts/search",
     *     summary="Search posts",
     *     description="Search posts by query. It can be by title or Ticker/ISIN.",
     *     operationId="searchPosts",
     *     tags={"Posts"},
     *     security={{ "jwt": {} }},
     *
     *     @OA\Parameter(
     *      name="Lang",
     *     in="header",
     *     description="Language for the response",
     *     required=false,
     *
     *     @OA\Schema(type="string", default="en"),
     *     ),
     *
     *     @OA\Parameter(
     *     name="query",
     *     in="query",
     *     description="Query for search",
     *     required=true,
     *
     *     @OA\Schema(type="string"),
     *     ),
     *
     *     @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *
     *     @OA\JsonContent(
     *     type="object",
     *
     *     @OA\Property(property="message", type="string", example="Success message"),
     *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PostResource")),
     *     ),
     *     ),
     *
     *     @OA\Response(response=400, description="Bad request"),
     *     )
     */
    public function searchPosts(SearchRequest $request): JsonResponse
    {
        $data = $request->validated();
        $posts = $this->postService->searchPost($data['query']);

        return self::sendSuccess(
            __('response.success'),
            PostCollection::make($posts)->jsonSerialize(),
        );
    }
}
