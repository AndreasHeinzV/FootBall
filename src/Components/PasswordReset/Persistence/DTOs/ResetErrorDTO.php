<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Persistence\DTOs;

class ResetErrorDTO
{

    public ?string $emptyFirstPW = null;
    public ?string $emptyLastPW = null;
    public ?string $firstPWValidationError = null;
    public ?string $lastPWValidationError = null;
    public ?string $differentPW = null;


}