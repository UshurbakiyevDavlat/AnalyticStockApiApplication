<?php

namespace App\Http\Middleware;

use App\Enums\AuthStrEnum;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array<int, string>
     */
    protected $except = [
        AuthStrEnum::JWT_NAME->value,
        AuthStrEnum::SOURCE_COOKIE->value,
    ];
}
