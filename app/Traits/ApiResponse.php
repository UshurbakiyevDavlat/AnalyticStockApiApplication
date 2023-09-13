<?php

namespace App\Traits;

trait ApiResponse
{
    /**
     * Успешный ответ API
     *
     * @param $message
     * @param array $data
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function sendSuccess(string|null $message = null, array $data = [], int $statusCode = 200)
    {
        return response()->json([
            'success'   => true,
            'message'   => $message ?? __('response.success'),
            'data'      => $data,
        ], $statusCode, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Ответ API с ошибкой
     *
     * @param $message
     * @param $data
     * @param $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function sendError(string|null $message = null, array $data = [], int $statusCode = 400)
    {
        return response()->json([
            'success'   => false,
            'message'   => $message ?? __('response.error'),
            'data'      => $data,
        ], $statusCode, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }
}
