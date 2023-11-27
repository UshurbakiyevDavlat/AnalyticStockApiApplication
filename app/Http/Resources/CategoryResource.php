<?php

namespace App\Http\Resources;

use App\Enums\LangStrEnum;
use App\Helpers\TranslationHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * @var null Wrapper
     */
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => [
                LangStrEnum::RU->value => $this->title,
                LangStrEnum::ENG->value => TranslationHelper::getCategoryTranslation(
                    LangStrEnum::ENG->value,
                    $this->id
                ),
                LangStrEnum::KZ->value => TranslationHelper::getCategoryTranslation(
                    LangStrEnum::KZ->value,
                    $this->id
                ),
            ],
            'description' => [
                LangStrEnum::RU->value => $this->description,
                LangStrEnum::ENG->value => TranslationHelper::getCategoryTranslation(
                    LangStrEnum::ENG->value,
                    $this->id,
                    'description',
                ),
                LangStrEnum::KZ->value => TranslationHelper::getCategoryTranslation(
                    LangStrEnum::KZ->value,
                    $this->id,
                    'description',
                ),
            ],
            'slug' => $this->slug,
            'img' => $this->img,
            'subcategories' => CategoryCollection::make($this->children),
        ];
    }
}
