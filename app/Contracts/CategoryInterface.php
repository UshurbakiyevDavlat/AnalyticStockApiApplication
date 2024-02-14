<?php

namespace App\Contracts;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

interface CategoryInterface
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
     *
     *     @OA\Parameter(
     *           name="Lang",
     *           in="header",
     *           description="Language for the response",
     *           required=false,
     *
     *           @OA\Schema(type="string", default="en"),
     *  ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="categories",
     *                 type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/CategoryResource")
     *             ),
     *         ),
     *     ),
     *
     *     @OA\Response(response=400, description="Bad request"),
     * )
     */
    public function getCategories(): JsonResponse;

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
     *
     *      @OA\Parameter(
     *           name="category",
     *           in="path",
     *           required=true,
     *           description="ID of the category",
     *
     *           @OA\Schema(type="integer"),
     *       ),
     *
     *     @OA\Parameter(
     *           name="Lang",
     *           in="header",
     *           description="Language for the response",
     *           required=false,
     *
     *           @OA\Schema(type="string", default="en"),
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(property="category", ref="#/components/schemas/CategoryResource"),
     *          ),
     *      ),
     *
     *      @OA\Response(response=400, description="Bad request"),
     * )
     *
     * @param Category $category The category to retrieve
     * @return JsonResponse
     */
    public function getCategory(Category $category): JsonResponse;
}
