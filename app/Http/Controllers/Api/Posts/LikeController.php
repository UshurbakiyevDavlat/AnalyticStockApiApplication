<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Posts;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class LikeController extends Controller
{
    /**
     * Get user likes.
     *
     * @OA\Get(
     *       path="/api/v1/posts/likes",
     *       summary="Get liked posts",
     *       description="Retrieve a list of posts liked by the authenticated user.",
     *       operationId="getLikes",
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
    public function getLikes(): JsonResponse
    {
        $user = auth()->user();

        return self::sendSuccess(
            __('response.success'),
            PostCollection::make($user->likes()->get())
                ->jsonSerialize(),
        );

    }
}
