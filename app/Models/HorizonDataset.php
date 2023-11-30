<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="HorizonDataset",
 *     type="object",
 *     required={"id", "column1", "column2", "created_at", "updated_at"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="column1", type="string"),
 *     @OA\Property(property="column2", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="post", type="object", ref="#/components/schemas/Post"),
 * )
 */
class HorizonDataset extends Model
{
    use HasFactory;

    /**
     * {@inheritDoc}
     */
    protected $table = 'post_horizon_dataset';

    /**
     * Post relationship
     *
     * @return HasOne
     */
    public function post(): HasOne
    {
        return $this->hasOne(Post::class, 'horizon_dataset_id');
    }
}
