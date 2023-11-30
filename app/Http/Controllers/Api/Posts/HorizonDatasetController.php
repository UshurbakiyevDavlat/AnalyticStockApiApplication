<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class HorizonDatasetController extends Controller
{
    /**
     * List horizon dataset
     *
     * @OA\Get(
     *       path="/api/v1/posts/horizonData/{post}",
     *       summary="List horizon dataset for a post",
     *       description="Retrieve the horizon dataset for a specific post.",
     *       operationId="getHorizonData",
     *       tags={"Posts"},
     *       security={{ "jwt": {} }},
     *       @OA\Parameter(
     *           name="post",
     *           in="path",
     *           description="ID of the post",
     *           required=true,
     *           @OA\Schema(type="integer"),
     *       ),
     *       @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="message", type="string", example="Success message"),
     *               @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/HorizonDataset")),
     *           ),
     *       ),
     *       @OA\Response(response=400, description="Bad request"),
     *  )
     *
     * @param Post $post
     * @return JsonResponse
     */
    public function list(Post $post): JsonResponse
    {
        return self::sendSuccess(
            __('response.success'),
            $post->horizonDataset()
                ->get()
                ->jsonSerialize(),
        );
    }
}
