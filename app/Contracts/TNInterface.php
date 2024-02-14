<?php

namespace App\Contracts;

use OpenApi\Annotations as OA;

interface TNInterface
{
    /**
     * Get shares and obligations data from the TN API
     *
     *
     * @OA\Get(
     *     path="/api/TNDictionary",
     *     summary="Get shares and obligations data",
     *     tags={"TN"},
     *     security={{ "jwt": {} }},
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     type="object",
     *     @OA\Property(property="message", type="string", example="Success"),
     *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Ticker")),
     *     ),
     *     ),
     *     @OA\Response(
     *     response=500,
     *     description="Internal Server Error",
     *     @OA\JsonContent(
     *     type="object",
     *     @OA\Property(property="message", type="string", example="Internal Server Error"),
     *     ),
     *     ),
     * )
     *
     * @return array
     */
    public function getSharesAndObligationsData(): array;
}
