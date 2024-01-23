<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Post",
 *     type="object",
 *     required={"id", "title", "desc", "content", "order", "ticker", "author_id", "type_paper_id", "status_id",
 *     "category_id", "country_id", "published_at", "expired_at", "created_at", "updated_at"},
 *
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="desc", type="string"),
 *     @OA\Property(property="content", type="string"),
 *     @OA\Property(property="order", type="integer"),
 *     @OA\Property(property="author_id", type="integer"),
 *     @OA\Property(property="type_paper_id", type="integer"),
 *     @OA\Property(property="status_id", type="integer"),
 *     @OA\Property(property="category_id", type="integer"),
 *     @OA\Property(property="country_id", type="integer"),
 *     @OA\Property(property="ticker_id", type="integer"),
 *     @OA\Property(property="published_at", type="string", format="date-time"),
 *     @OA\Property(property="expired_at", type="string", format="date-time"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="author", type="object", ref="#/components/schemas/User"),
 *     @OA\Property(property="type_paper", type="object", ref="#/components/schemas/TypePaper"),
 *     @OA\Property(property="status", type="object", ref="#/components/schemas/Status"),
 *     @OA\Property(property="category", type="object", ref="#/components/schemas/Category"),
 *     @OA\Property(property="country", type="object", ref="#/components/schemas/Country"),
 *     @OA\Property(property="ticker", type="object", ref="#/components/schemas/Ticker"),
 *     @OA\Property(property="likes", type="array", @OA\Items(ref="#/components/schemas/Like")),
 *     @OA\Property(property="views", type="array", @OA\Items(ref="#/components/schemas/PostView")),
 *     @OA\Property(property="subscriptions", type="array", @OA\Items(ref="#/components/schemas/Subscription")),
 *     @OA\Property(property="bookmarks", type="array", @OA\Items(ref="#/components/schemas/Favourite")),
 *     @OA\Property(property="horizon_dataset", type="object", ref="#/components/schemas/HorizonDataset"),
 *     @OA\Property(property="files", type="array", @OA\Items(ref="#/components/schemas/File")),
 * )
 *
 * @property string $title
 * @property bool $is_published
 */
class Post extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    /**
     * @const post view post id field name
     */
    private const POST_VIEW_POST_ID_FIELD = 'post_id';

    /**
     * @const subscription post id field name
     */
    private const SUBS_VIEW_POST_ID_FIELD = 'post_id';

    /**
     * @const user id field name
     */
    private const USER_ID_FIELD = 'user_id';

    /** {@inheritDoc} */
    protected $guarded = ['id'];

    /** {@inheritDoc} */
    protected $fillable = [
        'title',
        'desc',
        'content',
        'order',
        'author_id',
        'type_paper_id',
        'status_id',
        'category_id',
        'subcategory_id',
        'post_type_id',
        'published_at',
        'expired_at',
        'attachment',
        'uuid',
    ];

    /**
     * Get the author that owns the Post.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'author_id',
        );
    }

    /**
     * Get the typePaper that owns the Post.
     */
    public function typePaper(): BelongsTo
    {
        return $this->belongsTo(TypePaper::class, 'type_paper_id');
    }

    /**
     * Get the status that owns the Post.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    /**
     * Get the category that owns the Post.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * The tags that belong to the Post
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'posts_has_tags');
    }

    /**
     * Likes that belong to the Post
     */
    public function likes(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            Like::class,
            'likeable_id',
            'id',
            'id',
            self::USER_ID_FIELD,
        )->where(
            'likes.likeable_type',
            '=',
            __CLASS__,
        );
    }

    /**
     * Views that belong to the Post
     */
    public function views(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            PostView::class,
            self::POST_VIEW_POST_ID_FIELD,
            'id',
            'id',
            self::USER_ID_FIELD,
        );
    }

    /**
     * Subscriptions that belong to the Post
     */
    public function subscriptions(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            Subscription::class,
            self::SUBS_VIEW_POST_ID_FIELD,
            'id',
            'id',
            self::USER_ID_FIELD,
        );
    }

    /**
     * Bookmarks that belong to the Post
     */
    public function bookmarks(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            Favourite::class,
            'favouriteable_id',
            'id',
            'id',
            self::USER_ID_FIELD,
        )->where(
            'favourites.favouriteable_type',
            '=',
            __CLASS__,
        );
    }

    /**
     * Horizon dataset that belong to the Post
     */
    public function horizonDataset(): BelongsTo
    {
        return $this->belongsTo(HorizonDataset::class, 'horizon_dataset_id');
    }

    /**
     * Files that belong to the Post
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'post_id');
    }

    /**
     * Post type that belong to the Post
     */
    public function postType(): BelongsTo
    {
        return $this->belongsTo(PostType::class, 'post_type_id');
    }

    /**
     * Post translations that belong to the Post
     */
    public function translations(): HasMany
    {
        return $this->hasMany(PostTranslation::class);
    }

    /**
     * Get the name of the index associated with the model.
     */
    public function searchableAs(): string
    {
        return 'posts_index';
    }

    /**
     * Get the index-able data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->getKey(),
            'title' => $this->title,
            'ticker' => $this->horizonDataset->ticker?->short_name,
            'isin' => $this->horizonDataset->isin?->code,
        ];
    }

    /**
     * Get the value used to index the model.
     */
    public function getScoutKey(): mixed
    {
        return $this->id;
    }

    /**
     * Get the key name used to index the model.
     */
    public function getScoutKeyName(): mixed
    {
        return 'id';
    }

    /**
     * Determine if the model should be searchable.
     */
    public function shouldBeSearchable(): bool
    {
        return $this->is_published;
    }
}
