<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
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
        'post_type_id',
        'published_at',
        'expired_at',
    ];

    /**
     * Get the author that owns the Post.
     *
     * @return BelongsTo
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
     *
     * @return BelongsTo
     */
    public function typePaper(): BelongsTo
    {
        return $this->belongsTo(TypePaper::class, 'type_paper_id');
    }

    /**
     * Get the status that owns the Post.
     *
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    /**
     * Get the category that owns the Post.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * The tags that belong to the Post
     *
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'posts_has_tags');
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
            self::USER_ID_FIELD,
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
            self::POST_VIEW_POST_ID_FIELD,
            'id',
            'id',
            self::USER_ID_FIELD,
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
            self::SUBS_VIEW_POST_ID_FIELD,
            'id',
            'id',
            self::USER_ID_FIELD,
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
            self::USER_ID_FIELD,
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

    /**
     * Post type that belong to the Post
     *
     * @return BelongsTo
     */
    public function postType(): BelongsTo
    {
        return $this->belongsTo(PostType::class, 'post_type_id');
    }
}
