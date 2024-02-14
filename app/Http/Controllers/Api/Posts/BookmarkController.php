<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Posts;

use App\Contracts\BookmarkInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\BookmarkRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class BookmarkController extends Controller implements BookmarkInterface
{
    /** @inheritDoc */
    public function getBookmarks(): JsonResponse
    {
        $user = auth()->user();

        return self::sendSuccess(
            __('response.success'),
            $user->bookmarks->pluck('id')->toArray(),
        );
    }

    /** @inheritDoc */
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
