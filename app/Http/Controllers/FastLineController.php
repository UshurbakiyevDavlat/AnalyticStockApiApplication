<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Ticker;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class FastLineController extends Controller
{
    use ApiResponse;

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
     * )
     */
    public function list(): JsonResponse
    {
        return self::sendSuccess(
            __('response.success'),
            Ticker::where('is_favorite', true)->get(),
        );
    }
}
