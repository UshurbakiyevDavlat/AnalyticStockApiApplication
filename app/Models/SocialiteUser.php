<?php

namespace App\Models;

class SocialiteUser
{
    /**
     * @var string $email
     */
    public string $email;

    /**
     * @var string $token
     */
    public string $token;

    /**
     * @var string $name
     */
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
     * Get the unique identifier for the user.
     *
     * @return void
     */
    public function getId()
    {
        // Implement as needed
    }

    /**
     * Get the nickname / username for the user.
     *
     * @return void
     */
    public function getNickname()
    {
        // Implement as needed
    }

    /**
     * Get the name of the user.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the e-mail address of the user.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get the avatar / image URL for the user.
     *
     * @return void
     */
    public function getAvatar()
    {
        // Implement as needed
    }

    /**
     * Get the token value.
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}