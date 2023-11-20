<?php

namespace App\Http\Controllers;

use App\Enums\CacheIntEnum;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use OpenApi\Annotations as OA;

class CategoryController extends Controller
{
    use ApiResponse;

    /**
     * Get the list of categories.
     *
     * @OA\Get(
     *      path="/api/v1/categories",
     *      summary="Get categories list",
     *      description="Retrieve a list of categories.",
     *      operationId="getCategories",
     *      tags={"Category"},
     *      security={{"passport": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="categories", type="array", @OA\Items(ref="#/components/schemas/Category")),
     *          ),
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     * )
     *
     * @return JsonResponse
     */
    public function getCategories(): JsonResponse
    {
        $cacheKey = 'categories_list';

        // Attempt to retrieve categories from the cache
        $categories = Cache::remember(
            $cacheKey,
            now()->addMinutes(CacheIntEnum::EXPIRED->value),
            static function () {
                // Cache miss, fetch the categories from the database
                return CategoryCollection::make(
                    Category::whereNull('parent_id')
                        ->orderBy('order')
                        ->get(),
                );
            },
        );

        return self::sendSuccess('Categories list', ['categories' => $categories]);
    }

    /**
     * Get the concrete category.
     *
     * @OA\Get(
     *      path="/api/v1/categories/{category}",
     *      summary="Get category info API",
     *      description="Retrieve information about the category",
     *      operationId="getCategoryInfo",
     *      tags={"Category"},
     *      security={
     *           {"passport": {}},
     *       },
     *      @OA\Parameter(
     *           name="category",
     *           in="path",
     *           required=true,
     *           description="ID of the category",
     *           @OA\Schema(type="integer"),
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="user", ref="#/components/schemas/Category"),
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
        $cacheKey = 'categories_' . $category->id;

        $category = Cache::remember(
            $cacheKey,
            now()->addMinutes(CacheIntEnum::EXPIRED->value),
            static function () use ($category) {
                return CategoryResource::make($category);
            },
        );

        return self::sendSuccess('Category info', ['category' => $category]);
    }
}
