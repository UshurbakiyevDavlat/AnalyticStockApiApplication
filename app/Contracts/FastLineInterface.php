<?php

namespace App\Contracts;

use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

interface FastLineInterface
{
    /**
     * Get all Fast line tickers.
     *
     * @OA\Get(
     *     path="/api/v1/fastLine",
     *     summary="Get all fast line tickers",
     *     tags={"FastLine"},
     *
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *
     *     @OA\JsonContent(
     *     type="object",
     *
     *     @OA\Property(property="message", type="string", example="Success"),
     *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Ticker")),
     *     ),
     *     ),
     *
     *     @OA\Response(
     *     response=500,
     *     description="Internal Server Error",
     *
     *      @OA\JsonContent(
     *     type="object",
     *
     *     @OA\Property(property="message", type="string", example="Internal Server Error"),
     *     ),
     *     ),
     *     security={{ "jwt": {} }},
     *     )
     *
     * @return JsonResponse
     */
    public function list(): JsonResponse;
}
