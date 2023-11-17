<?php

namespace App\Models;

class SocialiteUser
{
    public string $email;

    public string $token;

    public string $name;

    public function __construct(string $email, string $token, string $name)
    {
        $this->name = $name;
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

    public function getName(): string
    {
        return $this->name;
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