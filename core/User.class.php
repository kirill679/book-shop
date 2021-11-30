<?php

class User
{
    public ?int $id;
    public string $login;
    public string $password;
    public ?string $email;
    public int $created;

    public function __construct(array $user = [])
    {
        if ($user) {
            $this->id       = $user['id'] ?? null;
            $this->login    = $user['login'];
            $this->password = $user['password'];
            $this->email    = $user['email'] ?? null;
            $this->created  = $user['created'] ?? time();
        }
    }
}