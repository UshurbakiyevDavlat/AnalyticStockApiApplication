<?php

namespace App\Enums;

enum AuthIntEnum: int
{
    /**
     * min depends on the token expiration time
     *
     * @const int EXPIRED
     */
    case EXPIRED = 3600; // minutes jwt token expiration time
}
