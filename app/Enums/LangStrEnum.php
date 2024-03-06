<?php

namespace App\Enums;

/**
 * Class LangStrEnum
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
}
