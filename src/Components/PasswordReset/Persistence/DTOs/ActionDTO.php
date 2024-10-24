<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Persistence\DTOs;

class ActionDTO
{
    public ?int $userId = null;
    public ?string $actionId = null;
    public ?int $timestamp = null;
}