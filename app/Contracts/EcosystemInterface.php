<?php

namespace App\Contracts;

use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

interface EcosystemInterface
{
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
     *
     * @return JsonResponse
     */
    public function getEcosystem(): JsonResponse;
}
