<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Isin",
 *     type="object",
 *     required={"id", "code", "created_at", "updated_at"},
 *
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="code", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 *
 * @property string $code
 * @method static updateOrCreate(array $array, true[] $array1)
 */
class Isin extends Model
{
    use HasFactory;

    /** {@inheritdoc} */
    protected $table = 'isins';

    /** {@inheritdoc} */
    protected $fillable = [
        'code',
        'is_active',
        'is_favorite',
    ];
}
