<?php

namespace App\Enums;

/**
 * Class LangStrEnum
 *
 * @package App\Enums
 */
enum LangStrEnum: string
{
    /**
     * @const string LANG_HEADER
     */
    case LANG_HEADER = 'Lang';

    /**
     * @const string RU
     */
    case RU = 'ru';

    /**
     * @const string ENG
     */
    case ENG = 'en';

    /**
     * @const string KZ
     */
    case KZ = 'kz';

    /**
     * @const string TITLE
     */
    case TITLE = 'title';

    /**
     * @const string DESC
     */
    case DESC = 'description';

    /**
     * @const string POST_DESC post model description
     */
    case POST_DESC = 'desc';

    /**
     * @const string CONTENT
     */
    case CONTENT = 'content';

    //Fields below should fit to name of the model.

    /**
     * @const string CATEGORIES category model name
     */
    case CATEGORIES = 'Category';

    /**
     * @const string POSTS post model name
     */
    case POSTS = 'Post';

    /**
     * @const string NO_TRANSLATION
     */
    case NO_TRANSLATION = 'No translation';

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
     * Get groups for translation.
     *
     * @return array
     */
    public static function getGroupsForTranslation(): array
    {
        return [
            self::CATEGORIES->value,
            self::POSTS->value,
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
     * Get params for translations enum groups.
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

            strtolower(self::POSTS->value) => [
                self::TITLE->value,
                self::POST_DESC->value,
                self::CONTENT->value,
            ],
        ];
    }
}
