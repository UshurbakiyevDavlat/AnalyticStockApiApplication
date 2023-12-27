<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PostView",
 *     type="object",
 *     required={"id", "post_id", "user_id", "created_at", "updated_at"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="post_id", type="integer"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="post", type="object", ref="#/components/schemas/Post"),
 *     @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
 * )
 */
class PostView extends Model
{
    use HasFactory;

    /**
     * {@inheritDoc}
     */
    protected $table = 'post_views';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'post_id',
        'user_id',
    ];

    /**
     * Post relationship
     *
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * User relationship
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
