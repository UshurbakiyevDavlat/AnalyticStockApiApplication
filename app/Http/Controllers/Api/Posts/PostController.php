<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Posts;

use App\Enums\PostStrEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\GetPostsRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Traits\FilterTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use OpenApi\Annotations as OA;

class PostController extends Controller
{
    use FilterTrait;

    /**
     * @const int PAGINATE_LIMIT
     */
    private const PAGINATE_LIMIT = 5;

    /**
     * Get all posts.
     *
     * @OA\Get(
     *        path="/api/v1/posts",
     *        summary="Get all posts",
     *        description="Retrieve a list of all posts.",
     *        operationId="getPosts",
     *        tags={"Posts"},
     *        security={{ "jwt": {} }},
     *        @OA\Response(
     *            response=200,
     *            description="Successful operation",
     *            @OA\JsonContent(
     *                type="object",
     *                @OA\Property(property="message", type="string", example="Success message"),
     *                @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PostResource")),
     *            ),
     *        ),
     *        @OA\Response(response=400, description="Bad request"),
     *   )
     *
     * @param GetPostsRequest $request Request object
     * @return JsonResponse
     */
    public function getPosts(GetPostsRequest $request): JsonResponse
    {
        $data = $request->validated();
        $query = Post::query()->with('horizonDataset');

        $data = $this->prepareDataForFilter($data);

        foreach ($data as $key => $value) {
            if ($key === 'sort') {
                $query = $this->applySort($query, $value);
            } elseif (!in_array($key, PostStrEnum::timePeriods(), true)) {
                if (in_array($key, PostStrEnum::getRelationColums(), true)) {
                    $relations = PostStrEnum::getRelationFilterValues();
                    foreach ($relations as $relation => $item) {
                        $query = $this->applyRelationFilter($query, $value, $relation, $item);
                    }
                } else {
                    $column = PostStrEnum::getFilterColumn($key);
                    if (!$column) {
                        continue;
                    }
                    $query = $this->applyFilter($query, $value, $column);
                }
            }
        }

        $publishedAt = isset($data['start_date'])
            ? Carbon::createFromTimestamp($data['start_date'])->format('Y-m-d H:i:s')
            : null;
        $expiredAt = isset($data['end_date'])
            ? Carbon::createFromTimestamp($data['end_date'])->format('Y-m-d H:i:s')
            : null;

        $query = $this->applyTimePeriodFilter($query, 'published_at', [$publishedAt, $expiredAt]);
        $post = $query->get();

        return self::sendSuccess(
            __('response.success'),
            PostCollection::make(
                $post,
            )->jsonSerialize(),
        );
    }

    /**
     * Get post by id.
     *
     * @OA\Get(
     *       path="/api/v1/posts/{post}",
     *       summary="Get post by ID",
     *       description="Retrieve a specific post by ID.",
     *       operationId="getPost",
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
     *               @OA\Property(property="data", type="object", ref="#/components/schemas/PostResource"),
     *           ),
     *       ),
     *       @OA\Response(response=400, description="Bad request"),
     *       @OA\Response(response=404, description="Post not found"),
     *  )
     *
     * @param Post $post
     * @return JsonResponse
     */
    public function getPost(Post $post): JsonResponse
    {
        return self::sendSuccess(
            __('response.success'),
            PostResource::make($post)->jsonSerialize(),
        );
    }

    /**
     * Prepare data for filter.
     *
     * @param array $data
     * @return array
     */
    private function prepareDataForFilter(array $data): array
    {
        foreach ($data as $key => $value) {
            if (!in_array($key, PostStrEnum::getValuesToNotDecode(), true)) {
                $data[$key] = array_map('intval', explode(',', $value));
            }
        }

        return $data;
    }
}
