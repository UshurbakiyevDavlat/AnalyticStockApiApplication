<?php

declare(strict_types=1);

namespace Tymon\JWTAuth\Facades;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\Payload;

/**
 * @method static mixed setToken(JWT|array|string|null $token)
 * @method static mixed fromUser(JWTSubject $user)
 * @method static mixed refresh()
 * @method static toUser()
 * @method static authenticate()
 *
 * @see JWT
 * @see Payload
 * @see JWTAuth
 */
class JWTAuth {}
