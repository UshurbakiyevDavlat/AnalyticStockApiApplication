<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'title',
        'extension',
        'mime_type',
        'icon',
    ];
}
