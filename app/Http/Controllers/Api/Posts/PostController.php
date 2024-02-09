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
use Exception;
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
     *           @OA\Schema(type="string", default="ru"),
     *  ),
     *   @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Sorting criteria for posts, should be and encoded. Like this 1%2C2%2C3%2C4%2C5",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Categories to filter posts, should be and encoded. Like this 1%2C2%2C3%2C4%2C5",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="subCategory",
     *         in="query",
     *         description="Subcategories to filter posts, should be and encoded. Like this 1%2C2%2C3%2C4%2C5",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sector",
     *         in="query",
     *         description="Sectors to filter posts, should be and encoded. Like this 1%2C2%2C3%2C4%2C5",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="author",
     *         in="query",
     *         description="Authors to filter posts, should be and encoded. Like this 1%2C2%2C3%2C4%2C5",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="ticker",
     *         in="query",
     *         description="Tickers to filter posts, should be and encoded. Like this 1%2C2%2C3%2C4%2C5",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="country",
     *         in="query",
     *         description="Countries to filter posts, should be and encoded. Like this 1%2C2%2C3%2C4%2C5",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="isin",
     *         in="query",
     *         description="ISINs to filter posts, should be and encoded. Like this 1%2C2%2C3%2C4%2C5",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Start date for posts, should be a timestamp like this 1704564000000",
     *         required=false,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="End date for posts, should be a timestamp like this 1704564000000",
     *         required=false,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="ID of the post",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="tags",
     *         in="query",
     *         description="Tags to filter posts, should be and encoded. Like this 1%2C2%2C3%2C4%2C5",
     *         required=false,
     *         @OA\Schema(type="string")
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
     *                @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PostResource")),
     *            ),
     *        ),
     *
     *        @OA\Response(response=400, description="Bad request"),
     *   )
     *
     * @param GetPostsRequest $request Request object
     * @throws Exception
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

    /**
     * Get posts user data.
     *
     * @OA\Get(
     *     path="/api/v1/posts/commonUserData",
     *     summary="Get posts commonUserData",
     *     description="Retrieve a list of all posts with user data.",
     *     operationId="getPostsUserData",
     *     tags={"Posts"},
     *     security={{ "jwt": {} }},
     *     @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\JsonContent(
     *     type="object",
     *     @OA\Property(property="message", type="string", example="Success message"),
     *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PostUserDataResource")),
     *     ),
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     )
     *
     * @return JsonResponse
     */
    public function getPostsUserData(): JsonResponse
    {
        return self::sendSuccess(__('response.success'), $this->postService->getPostUserData());
    }
}
