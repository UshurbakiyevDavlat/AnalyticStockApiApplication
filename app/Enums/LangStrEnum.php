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
     * @var string RU
     */
    case LANG_HEADER = 'Lang';

    /**
     * @var string RU
     */
    case RU = 'ru';

    /**
     * @var string ENG
     */
    case ENG = 'en';

    /**
     * @var string KZ
     */
    case KZ = 'kz';

    /**
     * @var string TITLE
     */
    case TITLE = 'title';

    /**
     * @var string DESC
     */
    case DESC = 'description';

    /**
     * @var string POST_DESC post model description
     */
    case POST_DESC = 'desc';

    /**
     * @var string CONTENT
     */
    case CONTENT = 'content';

    //Fields below should fit to name of the model.

    /**
     * @var string CATEGORIES category model name
     */
    case CATEGORIES = 'Category';

    /**
     * @var string POSTS post model name
     */
    case POSTS = 'Post';

    /**
     * @var string NO_TRANSLATION
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
