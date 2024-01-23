<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CategoryTranslation",
 *     type="object",
 *     title="CategoryTranslation",
 *     description="CategoryTranslation model",
 *
 *     @OA\Property(property="id", type="integer", example="1"),
 *     @OA\Property(property="title", type="string", example="Category title"),
 *     @OA\Property(property="description", type="string", example="Category description"),
 *     @OA\Property(property="category_id", type="integer", example="1"),
 *     @OA\Property(property="locale_id", type="integer", example="1"),
 *     @OA\Property(property="created_at", type="string", example="2021-09-01 00:00:00"),
 *     @OA\Property(property="updated_at", type="string", example="2021-09-01 00:00:00"),
 *     )
 */
class CategoryTranslation extends Model
{
    use HasFactory;

    /** {@inheritDoc} */
    protected $table = 'category_translations';

    /** {@inheritDoc} */
    protected $fillable = [
        'title',
        'description',
        'category_id',
        'locale_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the status that owns the CategoryTranslation.
     */
    public function locale(): BelongsTo
    {
        return $this->belongsTo(Locale::class);
    }
}
