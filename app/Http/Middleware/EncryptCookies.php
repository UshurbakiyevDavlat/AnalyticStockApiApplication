<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array<int, string>
     */
    protected $except = [
        'dev_jwt_research',
        'stage_jwt_research',
        'production_jwt_research',
        'dev_source',
        'stage_source',
        'production_source',
    ];
}
