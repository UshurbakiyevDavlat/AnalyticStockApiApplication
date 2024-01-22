<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Country",
 *     type="object",
 *     required={"id", "title", "created_at", "updated_at"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 *
 * @property string $title
 */
class Country extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * {@inheritDoc}
     */
    protected $guarded = ['id'];

    /**
     * {@inheritDoc}
     */
    protected $fillable = ['title'];
}
