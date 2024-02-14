<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Posts;

use App\Contracts\FilterDataListInterface;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Isin;
use App\Models\Sector;
use App\Models\Tag;
use App\Models\Ticker;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class FilterDataListController extends Controller implements FilterDataListInterface
{
    use ApiResponse;

    /** @inheritDoc */
    public function getCountries(): JsonResponse
    {
        $countries = Country::all()->jsonSerialize();
        $countries = array_map(static function ($country) {
            $country['img'] = Storage::disk('admin')->url($country['img']);

            return $country;
        }, $countries);

        return self::sendSuccess(
            __('response.success'),
            $countries,
        );
    }

    /** @inheritDoc */
    public function getSectors(): JsonResponse
    {
        return self::sendSuccess(
            __('response.success'),
            Sector::all()->jsonSerialize(),
        );
    }

    /** @inheritDoc */
    public function getAuthors(): JsonResponse
    {
        return self::sendSuccess(
            __('response.success'),
            User::all()->jsonSerialize(),
        );
    }

    /** @inheritDoc */
    public function getTickers(): JsonResponse
    {
        return self::sendSuccess(
            __('response.success'),
            Ticker::all()->jsonSerialize(),
        );
    }

    /** @inheritDoc */
    public function getIsins(): JsonResponse
    {
        return self::sendSuccess(
            __('response.success'),
            Isin::all()->jsonSerialize(),
        );
    }

    /** @inheritDoc */
    public function getTags(): JsonResponse
    {
        return self::sendSuccess(
            __('response.success'),
            Tag::all()->jsonSerialize(),
        );
    }

    /** @inheritDoc */
    public function getCommonFilterData(): JsonResponse
    {
        return self::sendSuccess(
            __('response.success'),
            [
                'countries' => Country::all()->jsonSerialize(),
                'sectors' => Sector::all()->jsonSerialize(),
                'authors' => User::all()->jsonSerialize(),
                'tickers' => Ticker::all()->jsonSerialize(),
                'isins' => Isin::all()->jsonSerialize(),
                'tags' => Tag::all()->jsonSerialize(),
            ],
        );
    }
}
