<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Posts;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class CategoryController extends Controller
{
    /**
     * Get the list of categories.
     *
     * @OA\Get(
     *     path="/api/v1/posts/categories",
     *     summary="Get post categories list",
     *     description="Retrieve a list of post categories.",
     *     operationId="getCategories",
     *     tags={"Posts"},
     *     security={{ "jwt": {} }},
     *     @OA\Parameter(
     *           name="Lang",
     *           in="header",
     *           description="Language for the response",
     *           required=false,
     *           @OA\Schema(type="string", default="en"),
     *  ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="categories",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/CategoryResource")
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     * )
     *
     * @return JsonResponse
     */

    public function getCategories(): JsonResponse
    {
        // $cacheKey = 'categories_list';
        //
        // // Attempt to retrieve categories from the cache
        // $categories = Cache::remember(
        //     $cacheKey,
        //     now()->addMinutes(CacheIntEnum::EXPIRED->value),
        //     static function () {
        //         // Cache miss, fetch the categories from the database
        //         return CategoryCollection::make(
        //             Category::whereNull('parent_id')
        //                 ->orderBy('order')
        //                 ->get(),
        //         )->jsonSerialize();
        //     },
        // );

        $categories = CategoryCollection::make(
            Category::whereNull('parent_id')
                ->orderBy('order')
                ->get(),
        )->jsonSerialize();

        return self::sendSuccess('Categories list', $categories);
    }

    /**
     * Get the concrete category.
     *
     * @OA\Get(
     *      path="/api/v1/posts/categories/{category}",
     *      summary="Get post category info API",
     *      description="Retrieve information about the posts category",
     *      operationId="getCategoryInfo",
     *      tags={"Posts"},
     *      security={{ "jwt": {} }},
     *      @OA\Parameter(
     *           name="category",
     *           in="path",
     *           required=true,
     *           description="ID of the category",
     *           @OA\Schema(type="integer"),
     *       ),
     *     @OA\Parameter(
     *           name="Lang",
     *           in="header",
     *           description="Language for the response",
     *           required=false,
     *           @OA\Schema(type="string", default="en"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="category", ref="#/components/schemas/CategoryResource"),
     *          ),
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     * )
     *
     * @param Category $category
     * @return JsonResponse
     */
    public function getCategory(Category $category): JsonResponse
    {
        // $cacheKey = 'category_' . $category->id;
        //
        // $category = Cache::remember(
        //     $cacheKey,
        //     now()->addMinutes(CacheIntEnum::EXPIRED->value),
        //     static function () use ($category) {
        //         return CategoryResource::make($category)->jsonSerialize();
        //     },
        // );

        return self::sendSuccess('Category info', CategoryResource::make($category)->jsonSerialize());
    }
}
