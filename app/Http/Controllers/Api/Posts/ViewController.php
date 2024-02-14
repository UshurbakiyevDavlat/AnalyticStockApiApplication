<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Posts;

use App\Contracts\ViewInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\ViewRequest;
use App\Http\Resources\PostCollection;
use Illuminate\Http\JsonResponse;

class ViewController extends Controller implements ViewInterface
{
    /** @inheritDoc */
    public function getViews(): JsonResponse
    {
        $user = auth()->user();

        return self::sendSuccess(
            __('response.success'),
            PostCollection::make(
                $user->views()->get(),
            )->jsonSerialize(),
        );
    }

    /** @inheritDoc */
    public function viewPost(ViewRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = auth()->user();
        $view = $user->views()->where('post_id', $data['post_id'])->first();

        if ($view) {
            $user->views()->updateExistingPivot(
                $data['post_id'],
                ['updated_at' => now()],
            );
        } else {
            $user->views()->attach($data['post_id'], [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return self::sendSuccess(__('response.post.viewed'));
    }
}
