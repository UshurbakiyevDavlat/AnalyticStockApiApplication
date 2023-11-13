<?php

namespace App\Models;

class SocialiteUser
{
    public string $email;

    public string $token;

    public function __construct(string $email, string $token)
    {
        $this->email = $email;
        $this->token = $token;
    }

    public function getId()
    {
        // Implement as needed
    }

    public function getNickname()
    {
        // Implement as needed
    }

    public function getName()
    {
        // Implement as needed
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getAvatar()
    {
        // Implement as needed
    }

    public function getToken(): string
    {
        return $this->token;
    }
}