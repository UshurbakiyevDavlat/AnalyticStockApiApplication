<?php

namespace App\Enums;

enum AuthIntEnum: int
{
    /**
     * min depends on the token expiration time
     *
     * @var int
     */
    case EXPIRED = 1;
}
