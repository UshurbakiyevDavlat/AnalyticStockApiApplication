<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Posts;

use App\Contracts\SubscriptionInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\SubscriptionRequest;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller implements SubscriptionInterface
{
    /** @inheritDoc */
    public function getSubscriptions(): JsonResponse
    {
        $user = auth()->user();

        return self::sendSuccess(
            __('response.success'),
            $user->subscriptions->pluck('id')->toArray(),
        );
    }

    /** @inheritDoc */
    public function subscribeToCategory(SubscriptionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = auth()->user();

        $subscription = $user->subscriptions()
            ->where(
                'category_id',
                $data['category_id'],
            )
            ->first();

        if ($subscription) {
            $user->subscriptions()->detach($data['category_id']);
            $subscribed = 'unsubscribed';
        } else {
            $user->subscriptions()->attach($data['category_id'], [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $subscribed = 'subscribed';
        }

        return self::sendSuccess(
            __('response.post.category.' . $subscribed),
        );
    }
}
