<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
