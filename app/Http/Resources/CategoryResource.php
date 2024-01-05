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
        $amountOfSubscribers = $this->subscriptions->count();

        if ($this->children->count() > 0) {
            $uniqueUserIds = collect();

            $this->children->each(fn($child) => $uniqueUserIds->push(
                $child->subscriptions->pluck('user_id'),
            ));

            $uniqueUserIds = $uniqueUserIds->whenNotEmpty(
                fn($collection) => $collection->flatten(),
            )
                ->unique();

            $amountOfSubscribers = $uniqueUserIds->count();
        }

        $lang = $request->header(LangStrEnum::LANG_HEADER->value, LangStrEnum::RU->value);

        $title = $lang !== LangStrEnum::RU->value
            ? TranslationHelper::getCategoryTranslation(
                $lang,
                $this->id,
            )
            : $this->title;

        $description = $lang !== LangStrEnum::RU->value
            ? TranslationHelper::getCategoryTranslation(
                $lang,
                $this->id,
                'description',
            )
            : $this->description;

        return [
            'id' => $this->id,
            'title' => $title,
            'description' => $description,
            'amountOfSubscribers' => $amountOfSubscribers,
            'slug' => $this->slug,
            'img' => $this->img
                ? Storage::disk('admin')->url($this->img)
                : null,
            'subcategories' => CategoryCollection::make($this->children),
        ];
    }
}
