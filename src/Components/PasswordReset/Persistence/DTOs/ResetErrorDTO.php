<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Persistence\DTOs;

use AllowDynamicProperties;

class ResetErrorDTO
{

    public ?string $emptyFirstPW = null;
    public ?string $emptySecondPW = null;
    public ?string $firstPWValidationError = null;
    public ?string $secondPWValidationError = null;
    public ?string $differentPWerror = null;


}