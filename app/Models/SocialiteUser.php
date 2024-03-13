<?php

namespace App\Models;

class SocialiteUser
{
    public string $email;

    public string $token;

    public string $name;

    /**
     * @constructor SocialiteUser
     */
    public function __construct(string $email, string $token, string $name)
    {
        $this->name = $name;
        $this->email = $email;
        $this->token = $token;
    }

    /**
     * Get the name of the user.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the e-mail address of the user.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get the token value.
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
