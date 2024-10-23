<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Persistence\DTOs;

class MailDTO
{
    public ?string $email = null;
    public ?string $message = null;
    public ?int $timestamp = null;
    public ?string $actionId = null;
}