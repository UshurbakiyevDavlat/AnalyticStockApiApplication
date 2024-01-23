<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Locale",
 *     type="object",
 *     title="Locale",
 *     description="Locale model",
 *
 *     @OA\Property(property="id", type="integer", example="1"),
 *     @OA\Property(property="locale", type="string", example="en"),
 *     @OA\Property(property="created_at", type="string", example="2021-09-01 00:00:00"),
 *     @OA\Property(property="updated_at", type="string", example="2021-09-01 00:00:00"),
 *     )
 */
class Locale extends Model
{
    use HasFactory;

    /** {@inheritDoc} */
    protected $table = 'locales';

    /** {@inheritDoc} */
    protected $fillable = [
        'locale',
    ];

    /**
     * Get all the posts for the Locale
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get all the categories for the Locale
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
