<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Posts;

use App\Contracts\PostInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\GetPostsRequest;
use App\Http\Requests\Post\SearchRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;

class PostController extends Controller implements PostInterface
{
    public function __construct(private readonly PostService $postService) {}

    /** @inheritDoc */
    public function getPosts(GetPostsRequest $request): JsonResponse
    {
        $data = $request->validated();
        // $cacheKey = 'posts_list';
        $posts = $this->postService->getPosts($data);

        // $cachedPosts = Cache::remember(
        //     $cacheKey,
        //     now()->addMinutes(CacheIntEnum::EXPIRED->value),
        //     static function () use ($posts) {
        //         return PostCollection::make($posts, ['paginated' => true])->jsonSerialize();
        //     },
        // );

        return self::sendSuccess(
            __('response.success'),
            PostCollection::make($posts, ['paginated' => true])->jsonSerialize(),
        );
    }

    /** @inheritDoc */
    public function getPost(Post $post): JsonResponse
    {
        // $cacheKey = 'post_' . $post->id;

        // $cachedPost = Cache::remember(
        //     $cacheKey,
        //     now()->addMinutes(CacheIntEnum::EXPIRED->value),
        //     static function () use ($post) {
        //         return PostResource::make($post)->jsonSerialize();
        //     },
        // );

        return self::sendSuccess(__('response.success'), PostResource::make($post)->jsonSerialize());
    }

    /** @inheritDoc */
    public function searchPosts(SearchRequest $request): JsonResponse
    {
        $data = $request->validated();
        $posts = $this->postService->searchPost($data['query']);

        return self::sendSuccess(
            __('response.success'),
            PostCollection::make($posts)->jsonSerialize(),
        );
    }

    /** @inheritDoc */
    public function getPostsUserData(): JsonResponse
    {
        return self::sendSuccess(__('response.success'), $this->postService->getPostUserData());
    }
}
