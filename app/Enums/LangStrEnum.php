<?php

namespace App\Enums;

enum LangStrEnum: string
{
    case RU = 'ru';
    case ENG = 'en';
    case KZ = 'kz';
    case CATEGORIES = 'Category'; //should fit to name of the model, there is convention of this web application
    case TITLE = 'title';
    case DESC = 'description';

    /**
     * Get supported languages for translation. TODO need to move to the database
     *
     * @return array
     */
    public static function getSupportedLangs(): array
    {
        return [
            self::ENG->value,
            self::KZ->value,
        ];
    }

    /**
     * Get groups for translation. TODO need to move to the database
     *
     * @return array
     */
    public static function getGroupsForTranslation(): array
    {
        return [
            self::CATEGORIES->value,
        ];
    }

    /**
     * Get params for translation of the given group.
     *
     * @param string $group
     * @return array
     */
    public static function getParamsForTranslation(string $group): array
    {
        $params = self::getParamsForGroup();

        return $params[$group];
    }

    /**
     * Get params for translations enum groups. TODO need to move to the database
     *
     * @return array[]
     */
    private static function getParamsForGroup(): array
    {
        return [
            strtolower(self::CATEGORIES->value) => [
                self::TITLE->value,
                self::DESC->value,
            ],
        ];
    }
}
