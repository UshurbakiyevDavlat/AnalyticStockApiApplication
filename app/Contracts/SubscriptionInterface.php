<?php

namespace App\Contracts;

use App\Http\Requests\Post\SubscriptionRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

interface SubscriptionInterface
{
    /**
     * Get all subscriptions for the authenticated user.
     *
     * @OA\Get(
     *       path="/api/v1/posts/categories/subscriptions",
     *       summary="Get subscribed posts category",
     *       description="Retrieve a list of posts categories subscribed by the authenticated user.",
     *       operationId="getCategorySubscriptions",
     *       tags={"Posts"},
     *       security={{ "jwt": {} }},
     *
     *       @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *
     *           @OA\JsonContent(
     *               type="object",
     *
     *               @OA\Property(property="message", type="string", example="Success message"),
     *               @OA\Property(property="data", type="array",
     *
     *     @OA\Items(ref="#/components/schemas/CategoryResource")),
     *           ),
     *       ),
     *
     *       @OA\Response(response=400, description="Bad request"),
     *  )
     *
     * @return JsonResponse
     */
    public function getSubscriptions(): JsonResponse;

    /**
     * Subscribe or unsubscribe a post category.
     *
     * @OA\Post(
     *        path="/api/v1/posts/categories/subscriptions",
     *        summary="Subscribe or unsubscribe post category",
     *        description="Subscribe or unsubscribe post category of authenticated user.",
     *        operationId="subscribeToCategory",
     *        tags={"Posts"},
     *        security={{ "jwt": {} }},
     *
     *       @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="category_id", type="integer", example="1"),
     *         ),
     *     ),
     *
     *        @OA\Response(
     *            response=200,
     *            description="Successful operation",
     *
     *            @OA\JsonContent(
     *                type="object",
     *
     *                @OA\Property(property="message", type="string", example="Success message"),
     *            ),
     *        ),
     *
     *        @OA\Response(response=400, description="Bad request"),
     *   )
     *
     * @param SubscriptionRequest $request The request object.
     * @return JsonResponse
     */
    public function subscribeToCategory(SubscriptionRequest $request): JsonResponse;
}
