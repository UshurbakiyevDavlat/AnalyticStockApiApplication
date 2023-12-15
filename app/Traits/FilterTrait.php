<?php

namespace App\Traits;

use App\Enums\FilterStrEnum;
use Illuminate\Database\Eloquent\Builder;

trait FilterTrait
{
    /**
     * Apply sorting for the query.
     *
     * @param Builder $query $query
     * @param string $sort sorting
     * @return Builder
     */
    public function applySort(Builder $query, string $sort): Builder
    {
        switch ($sort) {
            case 'date':
                $query->orderBy('created_at', FilterStrEnum::DESC->value);
                break;

            case 'popularity':
                $query->orderBy('views', FilterStrEnum::DESC->value);
                break;

            case 'likes':
                $query->orderBy('likes', FilterStrEnum::DESC->value);
                break;
        }

        return $query;
    }

    /**
     * Apply filters to query.
     *
     * @param Builder $query query
     * @param array $value value
     * @param string $column column
     * @return Builder
     */
    public function applyFilter(
        Builder $query,
        array $value,
        string $column,
    ): Builder {
        $query->whereIn($column, $value);

        return $query;
    }

    /**
     * Apply filters to query.
     *
     * @param Builder $query query
     * @param array $value value
     * @param string $relation
     * @param array $column column
     * @return Builder
     */
    public function applyRelationFilter(
        Builder $query,
        array $value,
        string $relation,
        array $column,
    ): Builder {
        foreach ($column as $item) {
            $query = $query
                ->whereHas($relation, function ($query) use ($item, $value) {
                    $query->whereIn($item, $value);
                });
        }

        return $query;
    }

    /**
     * Apply time period filter.
     *
     * @param Builder $query
     * @param string $key
     * @param array $value
     * @return Builder
     */
    public function applyTimePeriodFilter(
        Builder $query,
        string $key,
        array $value,
    ): Builder {
        $query->whereBetween($key, $value);

        return $query;
    }
}