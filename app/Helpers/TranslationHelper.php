<?php

namespace App\Helpers;

use App\Enums\LangStrEnum;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;

class TranslationHelper
{
    public static function getCategoryTranslation(
        string $lang,
        int $id,
        string $key = 'title',
    ): Application|array|string|Translator|\Illuminate\Contracts\Foundation\Application|null {
        $supportedLanguages = LangStrEnum::getSupportedLangs(); //TODO нужно будет добавить

        // Check if the requested language is supported
        if (in_array($lang, $supportedLanguages)) {
            App::setLocale($lang);
            $key = "category.{$id}.$key";

            return Lang::has($key)
                ? Lang::get($key)
                : 'No translation';
        }

        return 'Not supported language';
    }
}