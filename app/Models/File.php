<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="File",
 *     type="object",
 *     required={"id", "title", "path", "order", "file_type_id", "post_id", "created_at", "updated_at"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="path", type="string"),
 *     @OA\Property(property="order", type="integer"),
 *     @OA\Property(property="file_type_id", type="integer"),
 *     @OA\Property(property="post_id", type="integer"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="file_type", type="object", ref="#/components/schemas/FileType"),
 *     @OA\Property(property="post", type="object", ref="#/components/schemas/Post"),
 * )
 */
class File extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * {@inheritDoc}
     */
    protected $table = 'post_files';

    /**
     * {@inheritDoc}
     */
    protected $guarded = ['id'];

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'title',
        'path',
        'order',
        'file_type_id',
        'post_id',
    ];

    /**
     * Get the file type that owns the file.
     *
     * @return BelongsTo
     */
    public function fileType(): BelongsTo
    {
        return $this->belongsTo(FileType::class);
    }

    /**
     * Get the post that owns the File
     *
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
