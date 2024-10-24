<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Persistence\DTOs;

class ResetDTO
{
    public ?string $FirstPassword = null;
    public ?string $SecondPassword = null;

}