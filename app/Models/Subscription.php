<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Subscription",
 *     type="object",
 *     required={"id", "post_id", "user_id", "created_at", "updated_at"},
 *
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="post_id", type="integer"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="post", type="object", ref="#/components/schemas/Post"),
 *     @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
 * )
 */
class Subscription extends Model
{
    use HasFactory;

    /**
     * {@inheritDoc}
     */
    protected $table = 'post_category_subscription';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'post_id',
        'user_id',
    ];

    /**
     * Category relationship
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * User relationship
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
