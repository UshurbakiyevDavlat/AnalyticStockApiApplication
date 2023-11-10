<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Успешный ответ API
     *
     * @param string|null $message
     * @param array|null $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function sendSuccess(
        ?string $message = null,
        ?array $data = [],
        int $statusCode = 200,
    ): JsonResponse {
        return response()->json(
            [
                'success' => true,
                'message' => $message ?? __('response.success'),
                'data' => $data ?? [],
            ],
            $statusCode,
            [
                'Content-Type' => 'application/json;charset=UTF-8',
                'Charset' => 'utf-8',
            ],
            JSON_UNESCAPED_UNICODE,
        );
    }

    /**
     * Ответ API с ошибкой
     *
     * @param string|null $message
     * @param array|null $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function sendError(
        ?string $message = null,
        ?array $data = [],
        int $statusCode = 400,
    ): JsonResponse
    {
        return response()->json(
            [
                'success' => false,
                'message' => $message ?? __('response.error'),
                'data' => $data ?? [],
            ],
            $statusCode,
            [
                'Content-Type' => 'application/json;charset=UTF-8',
                'Charset' => 'utf-8',
            ],
            JSON_UNESCAPED_UNICODE,
        );
    }
}
