<?php

declare(strict_types=1);

namespace Tymon\JWTAuth\Facades;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\Payload;

/**
 * @method static Payload setToken(JWT|array|string|null $token)
 * @method static Payload fromUser(JWTSubject $user)
 * @method static Payload toUser(JWT|array|string|null $token)
 * @method static Payload refresh()
 * @see JWT
 */
class JWTAuth
{
}
