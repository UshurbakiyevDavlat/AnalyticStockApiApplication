<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\LangStrEnum;
use App\Models\HorizonDataset;
use App\Models\Like;
use App\Models\Locale;
use App\Models\PostTranslation;
use App\Models\PostType;
use App\Models\PostView;
use App\Models\TypePaper;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PostResource",
 *     type="object",
 *     required={"id", "title", "typeId", "categoryId", "subcategoriesId", "createdAt", "content", "likes", "views",
 *     "ticker", "country", "author"},
 *
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="typeId", type="string"),
 *     @OA\Property(property="categoryId", type="integer"),
 *     @OA\Property(property="subcategoriesId", type="array", @OA\Items(type="integer")),
 *     @OA\Property(property="createdAt", type="string", format="date-time"),
 *     @OA\Property(property="content", type="string"),
 *     @OA\Property(property="likes", type="integer"),
 *     @OA\Property(property="views", type="integer"),
 *     @OA\Property(
 *         property="ticker",
 *         type="object",
 *         @OA\Property(property="fullName", type="string"),
 *         @OA\Property(property="shortName", type="string"),
 *     ),
 *     @OA\Property(property="country", type="string"),
 *     @OA\Property(
 *         property="author",
 *         type="object",
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="name", type="string"),
 *         @OA\Property(property="avatar", type="string"),
 *     ),
 * )
 *
 * @property ?User $author
 * @property ?HorizonDataset $horizonDataset
 * @property ?PostType $postType
 * @property ?Like $likes
 * @property ?PostView $views
 * @property ?int $id
 * @property ?TypePaper $typePaper
 * @property int $category_id
 * @property int $subcategory_id
 * @property DateTime $created_at
 * @property DateTime $published_at
 * @property ?DateTime $expired_at
 * @property string $title
 * @property string $desc
 * @property string $content
 */
class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lang = $request->header(
            LangStrEnum::LANG_HEADER->value,
            LangStrEnum::RU->value,
        );

        $title = $this->title;
        $desc = $this->desc;
        $content = $this->content;

        if ($lang !== LangStrEnum::RU->value) {
            $lang_id = Locale::where(
                'locale',
                $lang,
            )
                ->first()
                ?->id;

            $postTranslation = PostTranslation::where('post_id', $this->id)
                ->where('locale_id', $lang_id)
                ->first();

            $title = $postTranslation->title
                ?? LangStrEnum::NO_TRANSLATION->value;

            $desc = $postTranslation->desc
                ?? LangStrEnum::NO_TRANSLATION->value;

            $content = $postTranslation->content
                ?? LangStrEnum::NO_TRANSLATION->value;
        }

        return [
            'id' => $this->id,
            'title' => $title,
            'desc' => $desc,
            'content' => $content,
            'typePaperTitle' => $this->typePaper?->title,
            'categoryId' => $this->category_id,
            'subcategoriesId' => $this->subcategory_id,
            'createdAt' => $this->created_at,
            'publishedAt' => $this->published_at,
            'expiredAt' => $this->expired_at,
            'likes' => $this->likes->count(),
            'views' => $this->views->count(),
            'sector' => $this->horizonDataset?->sector?->title,
            'postType' => $this->postType?->title,
            'ticker' => [
                'fullName' => $this->horizonDataset?->ticker?->full_name,
                'shortName' => $this->horizonDataset?->ticker?->short_name,
            ],
            'isin' => $this->horizonDataset?->isin?->code,
            'potential' => $this->horizonDataset?->potential,
            'country' => $this->horizonDataset?->country?->title,
            'author' => [
                'id' => $this->author?->id,
                'name' => $this->author?->name,
                'avatar' => $this->author?->avatar_url
                    ? Storage::disk('admin')->url($this->author->avatar_url ?? null)
                    : null,
                'job_title' => $this->author?->job_title,
            ],
        ];
    }
}
