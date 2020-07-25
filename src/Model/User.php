<?php

declare(strict_types=1);

namespace App\Model;

use function array_unique;
use function json_decode;

class User
{
    private int $id;
    private string $username;
    private string $email;
    private string $roles;
    private string $password;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getRoles(): array
    {
        return array_unique(json_decode($this->roles));
    }

    public function setRoles(string $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function __toString(): string
    {
        return $this->username;
    }
}
