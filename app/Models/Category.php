<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $fillable = ['title', 'order', 'parent_id', 'status_id'];

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__);
    }

    public function children(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }
}
