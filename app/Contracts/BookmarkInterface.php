<?php

namespace App\Contracts;

use App\Http\Requests\Post\BookmarkRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

interface BookmarkInterface
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
    public function getBookmarks(): JsonResponse;

    /**
     * Bookmark or undo bookmark a post.
     *
     * @OA\Post(
     *        path="/api/v1/posts/bookmarks",
     *        summary="Bookmark or unbookmark post",
     *        description="Bookmark or unbookmark a post of authenticated user.",
     *        operationId="bookmarkPost",
     *        tags={"Posts"},
     *        security={{ "jwt": {} }},
     *
     *       @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="favouriteable_id", type="integer", example="1"),
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
     * @param BookmarkRequest $request The request object
     * @return JsonResponse
     */
    public function bookmarkPost(BookmarkRequest $request): JsonResponse;
}
