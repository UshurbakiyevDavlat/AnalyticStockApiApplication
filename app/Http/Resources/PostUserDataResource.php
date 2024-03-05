<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * Class PostUserDataResource
 *
 * @property mixed $bookmarks
 * @property mixed $likes
 * @property mixed $subscriptions
 * @property mixed $views
 *
 * @package App\Http\Resources
 *
 * @OA\Schema(
 *     title="PostUserDataResource",
 *     description="PostUserData resource",
 *     @OA\Property(
 *     property="bookmarks",
 *     type="array",
 *     @OA\Items(type="integer")
 *    ),
 *     @OA\Property(
 *     property="likes",
 *     type="array",
 *     @OA\Items(type="integer")
 *   ),
 *     @OA\Property(
 *     property="subscription",
 *     type="array",
 *     @OA\Items(type="integer")
 *  ),
 *     @OA\Property(
 *     property="views",
 *     type="array",
 *     @OA\Items(type="integer")
 *  ),
 * )
 */
class PostUserDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request Request object
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'bookmarks' => $this->bookmarks->pluck('id')->toArray(),
            'likes' => $this->likes->pluck('id')->toArray(),
            'subscription' => $this->subscriptions->pluck('id')->toArray(),
            'views' => $this->views->pluck('id')->toArray(),
        ];
    }
}
