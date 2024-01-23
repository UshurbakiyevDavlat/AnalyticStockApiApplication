<?php

declare(strict_types=1);

namespace Illuminate\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

/**
 * @method static factory()
 * @method static getTTL()
 * @method void login(Authenticatable $user)
 * @method void loginUsingId(int|string $id)
 * @method bool check()
 * @method bool guest()
 */
class AuthManager {}
