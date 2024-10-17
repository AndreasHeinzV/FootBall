<?php

declare(strict_types=1);

namespace App\Components\User\Persistence\DTOs;

readonly class ErrorsDTO
{
    public function __construct(
        public string $firstNameEmptyError,
        public string $lastNameEmptyError,
        public string $emailError,
        public string $passwordError
    ) {
    }
}