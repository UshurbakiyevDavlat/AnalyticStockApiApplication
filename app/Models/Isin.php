<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Isin extends Model
{
    use HasFactory;

    /** @inheritdoc */
    protected $table = 'isins';

    /** @inheritdoc */
    protected $fillable = ['code'];
}
