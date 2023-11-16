<?php

namespace App\Enums;

enum AuthIntEnum: int
{
    /**
     * min depends on the token expiration and should be more than session expiration of admin panel
     *
     * @var int
     */
    case EXPIRED = 2;
}
