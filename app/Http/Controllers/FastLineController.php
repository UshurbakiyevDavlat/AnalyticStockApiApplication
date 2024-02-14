<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\FastLineInterface;
use App\Models\Ticker;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class FastLineController extends Controller implements FastLineInterface
{
    use ApiResponse;

    /** @inheritDoc */
    public function list(): JsonResponse
    {
        return self::sendSuccess(
            __('response.success'),
            Ticker::where('is_favorite', true)->get(),
        );
    }
}
