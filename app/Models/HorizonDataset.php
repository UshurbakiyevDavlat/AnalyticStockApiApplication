<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="HorizonDataset",
 *     type="object",
 *     required={"id", "column1", "column2", "created_at", "updated_at"},
 *
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="column1", type="string"),
 *     @OA\Property(property="column2", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="post", type="object", ref="#/components/schemas/Post"),
 * )
 *
 * @property string $potential
 * @property mixed $ticker
 * @property mixed $isin
 * @property mixed|null $country
 */
class HorizonDataset extends Model
{
    use HasFactory;

    /** {@inheritDoc} */
    protected $guarded = ['id'];

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
     * Security's ticker relation.
     *
     * @return MorphToMany
     */
    public function ticker(): MorphToMany
    {
        return $this->morphedByMany(
            Ticker::class,
            'security',
            'horizon_dataset_has_securities',
            'horizon_dataset_id',
            'security_id',
            'id',
        )
            ->withTimestamps();
    }

    /**
     * Security's isin relation.
     *
     * @return MorphToMany
     */
    public function isin(): MorphToMany
    {
        return $this->morphedByMany(
            Isin::class,
            'security',
            'horizon_dataset_has_securities',
            'horizon_dataset_id',
            'security_id',
            'id',
        )
            ->withTimestamps();
    }

    /**
     * Country's relation.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Sector's relation.
     */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }
}
