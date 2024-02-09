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
     *
     * @return Builder
     */
    public function applySort(Builder $query, string $sort): Builder
    {
        switch ($sort) {
            case 'date':
                $query->orderBy('created_at', FilterStrEnum::DESC->value);
                break;

            case 'popularity':
                $query
                    ->withCount(['views'])
                    ->orderBy('views_count', FilterStrEnum::DESC->value);
                break;

            case 'likes':
                $query
                    ->withCount(['likes'])
                    ->orderBy('likes_count', FilterStrEnum::DESC->value);
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
     *
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
     * @param string $key key
     *
     * @return Builder
     */
    public function applyRelationFilter(
        Builder $query,
        array $value,
        string $relation,
        array $column,
        string $key,
    ): Builder {
        foreach ($column as $itemkey => $itemVal) {
            if ($itemkey === $key) {
                $query = $query
                    ->whereHas($relation, function ($query) use ($itemVal, $value) {
                        $query->whereIn($itemVal, $value);
                    });
            }
        }

        return $query;
    }

    /**
     * Apply time period filter.
     *
     * @param Builder $query query
     * @param array $value value
     *
     * @return Builder
     */
    public function applyTimePeriodFilter(
        Builder $query,
        array $value,
    ): Builder {
        if (isset($value[FilterStrEnum::START_DATE->value])) {
            $query = $query->where(
                'created_at',
                '>=',
                $value[FilterStrEnum::START_DATE->value],
            );
        }

        if (isset($value[FilterStrEnum::END_DATE->value])) {
            $query = $query->where(
                'created_at',
                '<=',
                $value[FilterStrEnum::END_DATE->value],
            );
        }

        return $query;
    }
}
