<?php

namespace App\Http\Resources;

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
                'ru' => $this->title,
                'en' => TranslationHelper::getCategoryTranslation('en', $this->id),
                'kz' => TranslationHelper::getCategoryTranslation('kz', $this->id),
            ],
            'description' => [
                'ru' => $this->description,
                'en' => TranslationHelper::getCategoryTranslation(
                    'en',
                    $this->id,
                    'description',
                ),
                'kz' => TranslationHelper::getCategoryTranslation(
                    'kz',
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
