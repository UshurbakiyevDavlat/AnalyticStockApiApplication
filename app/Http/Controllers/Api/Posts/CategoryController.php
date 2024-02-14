<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Posts;

use App\Contracts\CategoryInterface;
use App\Enums\CacheIntEnum;
use App\Enums\StatusActivityEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller implements CategoryInterface
{
    /** @inheritDoc */
    public function getCategories(): JsonResponse
    {
        $cacheKey = 'categories_list';

        // // Attempt to retrieve categories from the cache
        // $categories = Cache::remember(
        //     $cacheKey,
        //     now()->addMinutes(CacheIntEnum::EXPIRED->value),
        //     static function () {
        //         // Cache miss, fetch the categories from the database
        //         return CategoryCollection::make(
        //             Category::whereNull('parent_id')
        //                 ->where('status_id', StatusActivityEnum::ACTIVE->value)
        //                 ->get(),
        //         )->jsonSerialize();
        //     },
        // );

        return self::sendSuccess(
            'Categories list',
            CategoryCollection::make(
                Category::whereNull('parent_id')
                    ->where('status_id', StatusActivityEnum::ACTIVE->value)
                    ->get(),
            )->jsonSerialize(),
        );
    }

    /** @inheritDoc */
    public function getCategory(Category $category): JsonResponse
    {
        $cacheKey = 'category_' . $category->id;

        // $category = Cache::remember(
        //     $cacheKey,
        //     now()->addMinutes(CacheIntEnum::EXPIRED->value),
        //     static function () use ($category) {
        //         return CategoryResource::make($category)->jsonSerialize();
        //     },
        // );

        return self::sendSuccess('Category info', CategoryResource::make($category)->jsonSerialize());
    }
}
