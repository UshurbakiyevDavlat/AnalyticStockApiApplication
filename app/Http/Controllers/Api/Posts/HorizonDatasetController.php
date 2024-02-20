<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Posts;

use App\Contracts\HorizonDatasetInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\HorizonDatasetResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class HorizonDatasetController extends Controller implements HorizonDatasetInterface
{
    /** @inheritDoc */
    public function list(Post $post): JsonResponse
    {
        $postHorizonDataset = $post->horizonDataset()->first();

        return self::sendSuccess(
            __('response.success'),
            HorizonDatasetResource::make($postHorizonDataset)->jsonSerialize(),
        );
    }
}
