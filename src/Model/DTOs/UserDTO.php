<?php

declare(strict_types=1);

namespace App\Model\DTOs;

readonly class UserDTO
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $password,
    ) {
    }
}