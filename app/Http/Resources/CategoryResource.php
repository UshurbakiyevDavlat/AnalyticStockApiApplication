<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\LangStrEnum;
use App\Helpers\TranslationHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CategoryResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(
 *         property="title",
 *         type="object",
 *         @OA\Property(property="ru", type="string"),
 *         @OA\Property(property="eng", type="string"),
 *         @OA\Property(property="kz", type="string"),
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="object",
 *         @OA\Property(property="ru", type="string"),
 *         @OA\Property(property="eng", type="string"),
 *         @OA\Property(property="kz", type="string"),
 *     ),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="img", type="string"),
 *     @OA\Property(
 *         property="subcategories",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/CategoryResource"),
 *     ),
 * )
 */
class CategoryResource extends JsonResource
{
    /**
     * @var null Wrapper
     */
    public static $wrap;

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
                    $this->id,
                ),
                LangStrEnum::KZ->value => TranslationHelper::getCategoryTranslation(
                    LangStrEnum::KZ->value,
                    $this->id,
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
            'img' => $this->img
                ? Storage::disk('admin')->url($this->img)
                : null,
            'subcategories' => CategoryCollection::make($this->children),
        ];
    }
}
