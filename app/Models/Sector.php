<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Sector",
 *     type="object",
 *     required={"id", "title", "created_at", "updated_at"},
 *
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 *
 * @property string $title
 */
class Sector extends Model
{
    use HasFactory;

    /** {@inheritdoc} */
    protected $table = 'sectors';

    /** {@inheritdoc} */
    protected $fillable = ['title'];
}
