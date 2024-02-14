<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Posts;

use App\Contracts\LikeInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\LikeRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller implements LikeInterface
{
    /** @inheritDoc */
    public function getLikes(): JsonResponse
    {
        $user = auth()->user();

        return self::sendSuccess(
            __('response.success'),
            $user->likes->pluck('id')->toArray(),
        );
    }

    /** @inheritDoc */
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
            __('response.post.' . $liked),
        );
    }
}
