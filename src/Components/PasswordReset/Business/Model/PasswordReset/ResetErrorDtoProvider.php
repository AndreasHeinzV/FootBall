<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Business\Model\PasswordReset;

use App\Components\PasswordReset\Persistence\DTOs\ResetDTO;
use App\Components\PasswordReset\Persistence\DTOs\ResetErrorDTO;

readonly class ResetErrorDtoProvider
{

    public function __construct(private ValidateResetErrors $validateResetErrors)
    {
    }


    public function getResetErrors(ResetDTO $resetDTO): ResetErrorDTO|false
    {
        $resetErrorDto = $this->validateResetErrors->validate($resetDTO);

        if ($resetErrorDto instanceof ResetErrorDTO) {
            return $resetErrorDto;
        }
        return false;
    }
}