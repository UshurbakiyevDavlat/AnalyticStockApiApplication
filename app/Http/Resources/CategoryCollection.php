<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CategoryCollection",
 *     type="object",
 *     @OA\Property(
 *         property="list",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/CategoryResource")
 *     ),
 * )
 */
class CategoryCollection extends ResourceCollection
{
    /**
     * @var null Wrapper
     */
    public static $wrap = null;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'list' => $this->collection->transform(function ($category) {
                return new CategoryResource($category);
            }),
        ];
    }
}
