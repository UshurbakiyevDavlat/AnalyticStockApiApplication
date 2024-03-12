<?php

namespace App\Enums;

enum EnvStrEnum: string
{
    case LOCAL_ENV = 'local';
    case TEST_ENV = 'testing';
}
