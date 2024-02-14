<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Contracts\EcosystemInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\EcosystemResource;
use App\Models\Ecosystem;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class EcosystemController extends Controller implements EcosystemInterface
{
    use ApiResponse;

    /** @inheritDoc */
    public function getEcosystem(): JsonResponse
    {
        return self::sendSuccess(
            __('response.success'),
            EcosystemResource::collection(Ecosystem::all()),
        );
    }
}
