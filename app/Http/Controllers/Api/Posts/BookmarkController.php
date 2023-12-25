<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\BookmarkRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class BookmarkController extends Controller
{
    /**
     * Get user bookmarks.
     *
     * @OA\Get(
     *       path="/api/v1/posts/bookmarks",
     *       summary="Get bookmarked posts",
     *       description="Retrieve a list of posts bookmarked by the authenticated user.",
     *       operationId="getBookmarks",
     *       tags={"Posts"},
     *       security={{ "jwt": {} }},
     *       @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="message", type="string", example="Success message"),
     *               @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PostResource")),
     *           ),
     *       ),
     *       @OA\Response(response=400, description="Bad request"),
     *  )
     *
     * @return JsonResponse
     */
    public function getBookmarks(): JsonResponse
    {
        $user = auth()->user();

        return self::sendSuccess(
            __('response.success'),
            $user->bookmarks->pluck('id')->toArray(),
        );
    }

    /**
     * Bookmark or unbookmark a post.
     *
     * @OA\Post(
     *        path="/api/v1/posts/bookmarks",
     *        summary="Bookmark or unbookmark post",
     *        description="Bookmark or unbookmark a post of authenticated user.",
     *        operationId="bookmarkPost",
     *        tags={"Posts"},
     *        security={{ "jwt": {} }},
     *       @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="favouriteable_id", type="integer", example="1"),
     *         ),
     *     ),
     *        @OA\Response(
     *            response=200,
     *            description="Successful operation",
     *            @OA\JsonContent(
     *                type="object",
     *                @OA\Property(property="message", type="string", example="Success message"),
     *            ),
     *        ),
     *        @OA\Response(response=400, description="Bad request"),
     *   )
     *
     * @param BookmarkRequest $request
     * @return JsonResponse
     */
    public function bookmarkPost(BookmarkRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = auth()->user();

        $bookmark = $user->bookmarks()
            ->where(
                'favouriteable_id',
                $data['favouriteable_id'],
            )
            ->first();

        if ($bookmark) {
            $user->bookmarks()->detach($data['favouriteable_id']);
            $bookmarked = 'unbookmarked';
        } else {
            $user->bookmarks()->attach(
                $data['favouriteable_id'],
                ['favouriteable_type' => Post::class],
            );
            $bookmarked = 'bookmarked';
        }

        return self::sendSuccess(
            __('response.post.' . $bookmarked),
        );
    }
}
