<?php

namespace App\Enums;

enum AuthStrEnum: string
{
    case DRIVER = 'azure';
    case JWT_DOMAIN = '.ffin.global';
    case JWT_NAME = 'jwt_research';
    case JWT_PATH = '/';
    case SOURCE_COOKIE = 'source';
    case SAME_SITE = 'None';
}
