<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Favourite",
 *     type="object",
 *     required={"id", "user_id", "favouriteable_id", "favouriteable_type", "created_at", "updated_at"},
 *
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="favouriteable_id", type="integer"),
 *     @OA\Property(property="favouriteable_type", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
 * )
 */
class Favourite extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'user_id',
        'favouriteable_id',
        'favouriteable_type',
    ];

    /**
     * {@inheritDoc}
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all the owning favouriteable models.
     */
    public function favouriteable(): MorphTo
    {
        return $this->morphTo();
    }
}
