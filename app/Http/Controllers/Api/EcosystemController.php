<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EcosystemResource;
use App\Models\Ecosystem;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class EcosystemController extends Controller
{
    use ApiResponse;

    /**
     * Get all ecosystems.
     *
     * @OA\Get(
     *     path="/api/v1/ecosystem",
     *     summary="Get all ecosystems",
     *     tags={"Ecosystem"},
     *
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *
     *     @OA\JsonContent(
     *     type="object",
     *
     *     @OA\Property(property="message", type="string", example="Success"),
     *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Ecosystem")),
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
    public function getEcosystem(): JsonResponse
    {
        return self::sendSuccess(
            __('response.success'),
            EcosystemResource::collection(Ecosystem::all()),
        );
    }
}
