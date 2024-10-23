<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Persistence\DTOs;

class ResetDTO
{
    public ?string $email;
    public ?string $FirstPassword = null;
    public ?string $LastPassword = null;

    public ?int $timestamp = null;
}