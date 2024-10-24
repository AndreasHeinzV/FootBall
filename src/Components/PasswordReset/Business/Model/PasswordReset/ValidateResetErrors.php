<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Business\Model\PasswordReset;

use App\Components\PasswordReset\Business\Model\PasswordReset\Validation\ValidateDuplicatePassword;
use App\Components\PasswordReset\Business\Model\PasswordReset\Validation\ValidateFirstPassword;
use App\Components\PasswordReset\Business\Model\PasswordReset\Validation\ValidateSecondPassword;
use App\Components\PasswordReset\Persistence\DTOs\ResetDTO;
use App\Components\PasswordReset\Persistence\DTOs\ResetErrorDTO;

readonly class ValidateResetErrors
{
    public function __construct(
        private ValidateFirstPassword $validateFirstPassword,
        private ValidateSecondPassword $validateSecondPassword,
        private ValidateDuplicatePassword $validateDuplicatePassword
    ) {
    }

    public function validate(ResetDTO $resetDTO): ResetErrorDTO|false
    {
        $resetErrorDTO = new ResetErrorDTO();
        $resetErrorDTO = $this->validateFirstPassword->validate($resetErrorDTO, $resetDTO);
        $resetErrorDTO = $this->validateSecondPassword->validate($resetErrorDTO, $resetDTO);
        $resetErrorDTO = $this->validateDuplicatePassword->validate($resetErrorDTO, $resetDTO);

        return $this->checkForErrors($resetErrorDTO);
    }

    private function checkForErrors(ResetErrorDTO $resetErrorDTO): ResetErrorDTO|false
    {
        foreach ($resetErrorDTO as $error) {
            if ($error !== null) {
                return $resetErrorDTO;
            }
        }
        return false;
    }
}