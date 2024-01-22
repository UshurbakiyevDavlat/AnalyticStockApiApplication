<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="HorizonDataset",
 *     type="object",
 *     required={"id", "column1", "column2", "created_at", "updated_at"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="column1", type="string"),
 *     @OA\Property(property="column2", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="post", type="object", ref="#/components/schemas/Post"),
 * )
 *
 * @property string $potential
 */
class HorizonDataset extends Model
{
    use HasFactory;

    /** {@inheritDoc} */
    protected $table = 'post_horizon_dataset';

    /** {@inheritDoc} */
    protected $fillable = [
        'currentPrice',
        'openPrice',
        'targetPrice',
        'potential',
        'recommend',
        'analyzePrice',
        'horizon',
        'status',
        'risk',
        'ticker_id',
        'country_id',
        'isin_id',
        'sector_id',
    ];

    /**
     * Ticker`s relation.
     *
     * @return BelongsTo
     */
    public function ticker(): BelongsTo
    {
        return $this->belongsTo(Ticker::class);
    }

    /**
     * Country's relation.
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Isin's relation.
     *
     * @return BelongsTo
     */
    public function isin(): BelongsTo
    {
        return $this->belongsTo(Isin::class);
    }

    /**
     * Sector's relation.
     *
     * @return BelongsTo
     */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    /**
     * Post's relation.
     *
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
