<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *    schema="PostTranslation",
 *    type="object",
 *    title="PostTranslation",
 *    description="PostTranslation model",
 *  @OA\Property(property="id", type="integer", example="1"),
 *  @OA\Property(property="title", type="string", example="Post title"),
 *  @OA\Property(property="content", type="string", example="Post content"),
 *  @OA\Property(property="desc", type="string", example="Post desc"),
 *  @OA\Property(property="post_id", type="integer", example="1"),
 *  @OA\Property(property="locale_id", type="integer", example="1"),
 *  @OA\Property(property="created_at", type="string", example="2021-09-01 00:00:00"),
 *  @OA\Property(property="updated_at", type="string", example="2021-09-01 00:00:00"),
 * )
 */
class PostTranslation extends Model
{
    use HasFactory;

    /** {@inheritDoc} */
    protected $table = 'post_translations';

    /** {@inheritDoc} */
    protected $fillable = [
        'title',
        'content',
        'desc',
        'post_id',
        'locale_id',
    ];

    /**
     * Get the post that owns the PostTranslation.
     *
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the locale that owns the PostTranslation.
     *
     * @return BelongsTo
     */
    public function locale(): BelongsTo
    {
        return $this->belongsTo(Locale::class);
    }
}
