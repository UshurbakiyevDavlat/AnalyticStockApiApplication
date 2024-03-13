<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Contracts\EcosystemInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\EcosystemCollection;
use App\Http\Resources\EcosystemResource;
use App\Models\Ecosystem;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class EcosystemController extends Controller implements EcosystemInterface
{
    use ApiResponse;

    /** @inheritDoc */
    public function getEcosystems(): JsonResponse
    {
        $ecosystems = EcosystemResource::make(Ecosystem::all());

        return self::sendSuccess(
            __('response.success'),
            EcosystemCollection::make($ecosystems),
        );
    }

    /** @inheritDoc */
    public function getEcosystem(Ecosystem $ecosystem): JsonResponse
    {
        return self::sendSuccess(
            __('response.success'),
            EcosystemResource::make($ecosystem),
        );
    }
}
