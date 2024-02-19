<?php

namespace App\Enums;

enum PostStrEnum: string
{
    case id = 'id';
    case sort = 'sort';
    case category = 'category';
    case subCategory = 'subCategory';
    case region = 'country';
    case sector = 'sector';
    case author = 'author';
    case ticker = 'ticker';
    case isin = 'isin';
    case start_date = 'start_date';
    case end_date = 'end_date';
    case tag = 'tag';

    /**
     * Get all values.
     *
     * @return array<string>
     */
    public static function getFilterValues(): array
    {
        return [
            self::id->value => 'id',
            self::category->value => 'category_id',
            self::subCategory->value => 'subcategory_id',
            self::author->value => 'author_id',
            self::start_date->value => 'published_at',
            self::end_date->value => 'expired_at',
        ];
    }

    /**
     * Get column name for relation filter.
     *
     * @return array<string>
     */
    public static function getRelationColumns(): array
    {
        return [
            self::region->value,
            self::sector->value,
            self::ticker->value,
            self::isin->value,
            self::tag->value,
        ];
    }

    /**
     * Get column name for filter.
     *
     * @return array
     */
    public static function getRelationFilterValues(): array
    {
        return [
            'horizonDataset' => [
                self::region->value => 'country_id',
                self::sector->value => 'sector_id',
                self::ticker->value => 'ticker_id',
                self::isin->value => 'isin_id',
            ],
            'tags' => [
                self::tag->value => 'tag_id',
            ],
        ];
    }

    /**
     * Get values that should not be decoded.
     *
     * @return array<string>
     */
    public static function getValuesToNotDecode(): array
    {
        return [
            self::sort->value,
            self::start_date->value,
            self::end_date->value,
        ];
    }

    /**
     * Get time periods.
     *
     * @return array<string>
     */
    public static function timePeriods(): array
    {
        return [
            self::start_date->value,
            self::end_date->value,
        ];
    }

    /**
     * Key is a filter name, value is a column name.
     *
     * @param string $key Filter name.
     * @return string|null
     */
    public static function getFilterColumn(string $key): ?string
    {
        return self::getFilterValues()[$key] ?? null;
    }
}
