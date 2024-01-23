<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="FileType",
 *     type="object",
 *     required={"id", "title", "extension", "mime_type", "icon", "created_at", "updated_at"},
 *
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="extension", type="string"),
 *     @OA\Property(property="mime_type", type="string"),
 *     @OA\Property(property="icon", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="files", type="array", @OA\Items(ref="#/components/schemas/File")),
 * )
 */
class FileType extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * {@inheritDoc}
     */
    protected $guarded = [
        'id',
    ];

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'title',
        'extension',
        'mime_type',
        'icon',
    ];

    /**
     * Files relationship
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }
}
