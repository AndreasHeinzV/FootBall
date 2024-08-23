<?php

declare(strict_types=1);

namespace App\Model\Mapper;

class User
{
    private string $fname;
    private string $lname;
    private string $email;
    private string $password;

    public function __construct(string $fname, string $lname, string $email, string $password)
    {
        $this->fname = $fname;
        $this->lname = $lname;
        $this->email = $email;
        $this->password = $password;
    }

    public function getFname(): string
    {
        return $this->fname;
    }

    public function getLname(): string
    {
        return $this->lname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}