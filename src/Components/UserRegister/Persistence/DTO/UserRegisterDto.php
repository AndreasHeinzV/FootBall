<?php

declare(strict_types=1);

namespace App\Components\UserRegister\Persistence\DTO;

class UserRegisterDto
{
    public string $firstName = '';
    public string $lastName = '';
    public string $email = '';
    public string $password = '';
}