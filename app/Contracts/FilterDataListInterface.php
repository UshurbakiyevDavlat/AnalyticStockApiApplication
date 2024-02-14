<?php

namespace App\Contracts;

use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

interface FilterDataListInterface
{
    /**
     * Get all countries for filter.
     *
     * @OA\Get(
     *  path="/api/v1/posts/horizonData/countries",
     *  summary="List countries",
     *  description="Retrieve the list of countries.",
     *  operationId="getCountries",
     *  tags={"Posts"},
     *  security={{ "jwt": {} }},
     *
     * @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *
     *     @OA\JsonContent(
     *     type="object",
     *
     *     @OA\Property(property="message", type="string", example="Success message"),
     *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Country")),
     *     ),
     *     ),
     *
     * @OA\Response(response=400, description="Bad request"),
     *     )
     *
     * @return JsonResponse
     */
    public function getCountries(): JsonResponse;

    /**
     * Get all sectors for filter.
     *
     * @OA\Get(
     *   path="/api/v1/posts/horizonData/sectors",
     *   summary="List sectors",
     *   description="Retrieve the list of sectors.",
     *   operationId="getSectors",
     *   tags={"Posts"},
     *   security={{ "jwt": {} }},
     *
     *  @OA\Response(
     *      response=200,
     *      description="Successful operation",
     *
     *      @OA\JsonContent(
     *      type="object",
     *
     *      @OA\Property(property="message", type="string", example="Success message"),
     *      @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Sector")),
     *      ),
     *      ),
     *
     *  @OA\Response(response=400, description="Bad request"),
     *      )
     *
     * @return JsonResponse
     */
    public function getSectors(): JsonResponse;

    /**
     * Get all authors for filter.
     *
     * @OA\Get(
     *   path="/api/v1/posts/horizonData/authors",
     *   summary="List authors",
     *   description="Retrieve the list of authors.",
     *   operationId="getAuthors",
     *   tags={"Posts"},
     *   security={{ "jwt": {} }},
     *
     *  @OA\Response(
     *      response=200,
     *      description="Successful operation",
     *
     *      @OA\JsonContent(
     *      type="object",
     *
     *      @OA\Property(property="message", type="string", example="Success message"),
     *      @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User")),
     *      ),
     *      ),
     *
     *  @OA\Response(response=400, description="Bad request"),
     *      )
     *
     * @return JsonResponse
     */
    public function getAuthors(): JsonResponse;

    /**
     * Get all tickers for filter.
     *
     * @OA\Get(
     *   path="/api/v1/posts/horizonData/tickers",
     *   summary="List tickers",
     *   description="Retrieve the list of tickers.",
     *   operationId="getTickers",
     *   tags={"Posts"},
     *   security={{ "jwt": {} }},
     *
     *  @OA\Response(
     *      response=200,
     *      description="Successful operation",
     *
     *      @OA\JsonContent(
     *      type="object",
     *
     *      @OA\Property(property="message", type="string", example="Success message"),
     *      @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Ticker")),
     *      ),
     *      ),
     *
     *  @OA\Response(response=400, description="Bad request"),
     *      )
     *
     * @return JsonResponse
     */
    public function getTickers(): JsonResponse;

    /**
     * Get isins for filter.
     *
     * @OA\Get(
     *   path="/api/v1/posts/horizonData/isins",
     *   summary="List isins",
     *   description="Retrieve the list of isins.",
     *   operationId="getIsins",
     *   tags={"Posts"},
     *   security={{ "jwt": {} }},
     *
     *  @OA\Response(
     *      response=200,
     *      description="Successful operation",
     *
     *      @OA\JsonContent(
     *      type="object",
     *
     *      @OA\Property(property="message", type="string", example="Success message"),
     *      @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Isin")),
     *      ),
     *      ),
     *
     *  @OA\Response(response=400, description="Bad request"),
     *      )
     *
     * @return JsonResponse
     */
    public function getIsins(): JsonResponse;

    /**
     * Get all tags for filter.
     *
     * @OA\Get(
     *     path="/api/v1/posts/tags",
     *     summary="List tags",
     *     description="Retrieve the list of tags.",
     *     operationId="getTags",
     *     tags={"Posts"},
     *     security={{ "jwt": {} }},
     *     @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\JsonContent(
     *     type="object",
     *     @OA\Property(property="message", type="string", example="Success message"),
     *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Tag")),
     *     ),
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     )
     *
     * @return JsonResponse
     */
    public function getTags(): JsonResponse;

    /**
     * Get all common filter data.
     *
     * @OA\Get(
     *     path="/api/v1/posts/commonFilterData",
     *     summary="List common filter data",
     *     description="Retrieve the list of common filter data.",
     *     operationId="getCommonFilterData",
     *     tags={"Posts"},
     *     security={{ "jwt": {} }},
     *     @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\JsonContent(
     *     type="object",
     *     @OA\Property(property="message", type="string", example="Success message"),
     *     @OA\Property(property="data", type="object",
     *     @OA\Property(property="countries", type="array", @OA\Items(ref="#/components/schemas/Country")),
     *     @OA\Property(property="sectors", type="array", @OA\Items(ref="#/components/schemas/Sector")),
     *     @OA\Property(property="authors", type="array", @OA\Items(ref="#/components/schemas/User")),
     *     @OA\Property(property="tickers", type="array", @OA\Items(ref="#/components/schemas/Ticker")),
     *     @OA\Property(property="isins", type="array", @OA\Items(ref="#/components/schemas/Isin")),
     *     @OA\Property(property="tags", type="array", @OA\Items(ref="#/components/schemas/Tag")),
     *     ),
     *     ),
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     )
     *
     * @return JsonResponse
     */
    public function getCommonFilterData(): JsonResponse;
}
