<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Ecosystem",
 *     type="object",
 *     required={"id", "website", "img", "created_at" ,"updated_at"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="website", type="string"),
 *     @OA\Property(property="img", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */
class Ecosystem extends Model
{
    use HasFactory;

    /** @inheritdoc */
    protected $table = 'ecosystem';

    /** @inheritdoc */
    protected $guarded = [
        'id',
    ];

    /** @inheritdoc */
    protected $fillable = [
        'website',
        'img',
    ];
}
