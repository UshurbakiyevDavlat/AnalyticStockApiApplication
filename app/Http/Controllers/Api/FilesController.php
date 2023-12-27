<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostFileResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class FilesController extends Controller
{
    /**
     * Get post files
     *
     * @OA\Get(
     *     path="/api/v1/posts/files/{post}",
     *     summary="Get post files",
     *     description="Retrieve files associated with a post.",
     *     operationId="getPostFiles",
     *     tags={"Posts"},
     *     security={{ "jwt": {} }},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Success message"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/File")),
     *         ),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Not Found"),
     * )
     *
     * @param Post $post
     * @return JsonResponse
     */
    public function getPostFiles(Post $post): JsonResponse
    {
        return self::sendSuccess(
            __('response.success'),
            PostFileResource::make($post)->jsonSerialize(),
        );
    }
}
