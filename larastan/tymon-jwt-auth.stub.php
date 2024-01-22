<?php

declare(strict_types=1);

namespace Tymon\JWTAuth;

use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @method static Payload setToken(JWT|array|string|null $token)
 * @method static Payload fromUser(JWTSubject $user)
 * @method static Payload refresh()
 * @method static toUser()
 * @method static authenticate()
 *
 * @see JWT
 */
class JWTAuth
{
}
