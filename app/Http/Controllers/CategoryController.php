<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
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
        return self::sendSuccess(
            'Categories list',
            [
                'categories' => Category::all(),
            ],
        );
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
        return self::sendSuccess(
            'Category info',
            [
                'category' => $category,
            ],
        );
    }
}
