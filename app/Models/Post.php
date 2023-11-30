<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Post",
 *     type="object",
 *     required={"id", "title", "desc", "content", "order", "ticker", "author_id", "type_paper_id", "status_id", "category_id", "country_id", "published_at", "expired_at", "created_at", "updated_at"},
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
 */
class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * {@inheritDoc}
     */
    protected $guarded = ['id'];

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'title',
        'desc',
        'content',
        'order',
        'ticker_id',
        'author_id',
        'type_paper_id',
        'status_id',
        'category_id',
        'country_id',
        'published_at',
        'expired_at',
    ];

    /**
     * The author that belong to the Post
     *
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * The type paper that belong to the Post
     *
     * @return BelongsTo
     */
    public function typePaper(): BelongsTo
    {
        return $this->belongsTo(TypePaper::class, 'type_paper_id');
    }

    /**
     * The status that belong to the Post
     *
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    /**
     * The category that belong to the Post
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * The country that belong to the Post
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * Get the ticker that owns the Post
     *
     * @return BelongsTo
     */
    public function ticker(): BelongsTo
    {
        return $this->belongsTo(Ticker::class, 'ticker_id');
    }

    /**
     * Likes that belong to the Post
     *
     * @return HasManyThrough
     */
    public function likes(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            Like::class,
            'likeable_id',
            'id',
            'id',
            'user_id',
        )->where(
            'likes.likeable_type',
            '=',
            __CLASS__,
        );
    }

    /**
     * Views that belong to the Post
     *
     * @return HasManyThrough
     */
    public function views(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            PostView::class,
            'post_id',
            'id',
            'id',
            'user_id',
        );
    }

    /**
     * Subscriptions that belong to the Post
     *
     * @return HasManyThrough
     */
    public function subscriptions(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            Subscription::class,
            'post_id',
            'id',
            'id',
            'user_id',
        );
    }

    /**
     * Bookmarks that belong to the Post
     *
     * @return HasManyThrough
     */
    public function bookmarks(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            Favourite::class,
            'favouriteable_id',
            'id',
            'id',
            'user_id',
        )->where(
            'favourites.favouriteable_type',
            '=',
            __CLASS__,
        );
    }

    /**
     * Horizon dataset that belong to the Post
     *
     * @return BelongsTo
     */
    public function horizonDataset(): BelongsTo
    {
        return $this->belongsTo(HorizonDataset::class, 'horizon_dataset_id');
    }

    /**
     * Files that belong to the Post
     *
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'post_id');
    }
}
