<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PostStrEnum;
use App\Models\Post;
use App\Traits\FilterTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class PostService
{
    use FilterTrait;

    /**
     * @var string TIME_FORMAT
     */
    private const DATE_FORMAT = 'Y-m-d H:i:s';
    /**
     * @var int TIME_COEFFICIENT
     */
    private const TIME_COEFFICIENT = 1000;
    /**
     * @var string TIME_ZONE
     */
    private const TIME_ZONE = 'Asia/Almaty';
    /**
     * @var int PAGINATE_LIMIT
     */
    private const PAGINATE_LIMIT = 10;
    /**
     * @var string SORT_TYPE
     */
    private const SORT_TYPE = 'sort';

    /**
     * Get posts with filter or not.
     *
     * @param array $data
     * @return LengthAwarePaginator
     */
    public function getPosts(array $data): LengthAwarePaginator
    {
        $query = Post::query()->with('horizonDataset')
            ->where('is_published', true);

        if (!empty($data)) {
            $data = $this->prepareDataForFilter($data);

            foreach ($data as $key => $value) {
                if ($key === self::SORT_TYPE) {
                    $query = $this->applySort($query, $value);
                } elseif (!in_array($key, PostStrEnum::timePeriods(), true)) {
                    if (in_array($key, PostStrEnum::getRelationColumns(), true)) {
                        $relations = PostStrEnum::getRelationFilterValues();
                        foreach ($relations as $relation => $item) {
                            $query = $this->applyRelationFilter($query, $value, $relation, $item, $key);
                        }
                    } else {
                        $column = PostStrEnum::getFilterColumn($key);
                        if (!$column) {
                            continue;
                        }
                        $query = $this->applyFilter($query, $value, $column);
                    }
                }
            }

            $publishedAt = isset($data['start_date'])
                ? Carbon::createFromTimestamp(
                    $data['start_date'] / self::TIME_COEFFICIENT,
                    self::TIME_ZONE,
                )
                    ->format(self::DATE_FORMAT)
                : null;

            $expiredAt = isset($data['end_date'])
                ? Carbon::createFromTimestamp(
                    $data['end_date'] / self::TIME_COEFFICIENT,
                    self::TIME_ZONE,
                )
                    ->format(self::DATE_FORMAT)
                : null;

            if ($publishedAt || $expiredAt) {
                $query = $this->applyTimePeriodFilter($query, [
                    'start_date' => $publishedAt,
                    'end_date' => $expiredAt,
                ]);
            }
        }

        return $query->paginate(self::PAGINATE_LIMIT);
    }

    /**
     * Prepare data for filter.
     *
     * @param array $data
     * @return array
     */
    private function prepareDataForFilter(array $data): array
    {
        foreach ($data as $key => $value) {
            if (!in_array($key, PostStrEnum::getValuesToNotDecode(), true)) {
                $data[$key] = array_map('intval', explode(',', $value));
            }
        }

        return $data;
    }
}