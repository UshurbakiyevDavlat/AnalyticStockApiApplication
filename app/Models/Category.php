<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Category",
 *     title="Category",
 *     description="Category model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Category Title"),
 *     @OA\Property(property="order", type="integer", example=1),
 *     @OA\Property(property="parent_id", type="integer", example=2),
 *     @OA\Property(property="status_id", type="integer", example=3),
 *     @OA\Property(property="slug", type="string", example="category-slug"),
 *     @OA\Property(property="description", type="string", example="Category Description"),
 *     @OA\Property(property="img", type="string", example="category-image.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(
 *         property="status",
 *         ref="#/components/schemas/Status"
 *     ),
 *     @OA\Property(
 *         property="parent",
 *         ref="#/components/schemas/Category"
 *     ),
 *     @OA\Property(
 *         property="children",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Category")
 *     ),
 * )
 */
class Category extends Model
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
        'order',
        'parent_id',
        'status_id',
        'slug',
        'description',
        'img',
    ];

    /**
     * Get the status that owns the category.
     *
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Get the parent that owns the category.
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__);
    }

    /**
     * Get all the children for the Category
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

    /**
     * Get all the subscriptions for the Category
     *
     * @return HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
