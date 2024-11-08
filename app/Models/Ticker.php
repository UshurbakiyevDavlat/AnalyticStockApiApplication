<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Ticker",
 *     type="object",
 *     required={"id", "full_name", "short_name", "created_at", "updated_at"},
 *
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="full_name", type="string"),
 *     @OA\Property(property="short_name", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="posts", type="array", @OA\Items(ref="#/components/schemas/Post")),
 * )
 *
 * @property string $short_name
 * @property string $full_name
 * @method static where(string $string, true $true)
 * @method static updateOrCreate(array $array, array $array1)
 */
class Ticker extends Model
{
    use HasFactory;

    /** {@inheritDoc} */
    protected $guarded = ['id'];

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'full_name',
        'short_name',
        'is_active',
        'is_favorite',
    ];

    /**
     * Post relationship
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Security's relation.
     *
     * @return BelongsToMany
     */
    public function securities(): BelongsToMany
    {
        return $this->belongsToMany(
            HorizonDataset::class,
            'horizon_dataset_has_securities',
            'security_id',
            'horizon_dataset_id'
        );
    }
}
