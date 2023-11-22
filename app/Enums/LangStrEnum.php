<?php

namespace App\Enums;

use App\Models\Category;

enum LangStrEnum: string
{
    case ENG = 'en';
    case KZ = 'kz';
    case CATEGORIES = 'Category'; //should fit to name of the model
    case TITLE = 'title';
    case DECS = 'description';

    public static function getSupportedLangs(): array
    {
        return [
            self::ENG->value,
            self::KZ->value,
        ];
    }

    public static function getGroupsForTranslation(): array
    {
        return [
            self::CATEGORIES->value,
        ];
    }

    public static function getParamsForTranslation(): array
    {
        return [
            self::TITLE->value,
            self::DECS->value,
        ];
    }
}
