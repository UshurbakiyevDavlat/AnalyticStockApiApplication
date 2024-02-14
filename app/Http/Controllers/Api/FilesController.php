<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Contracts\FilesInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostFileResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class FilesController extends Controller implements FilesInterface
{
    /** @inheritDoc */
    public function getPostFiles(Post $post): JsonResponse
    {
        return self::sendSuccess(
            __('response.success'),
            PostFileResource::make($post)->jsonSerialize(),
        );
    }
}
