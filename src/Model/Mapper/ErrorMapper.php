<?php

declare(strict_types=1);

namespace App\Model\Mapper;

use App\Model\DTOs\ErrorsDTO;

class ErrorMapper
{
    public function createErrorDTO(array $errors): ErrorsDTO
    {
        return new ErrorsDTO(
            $errors['firstNameEmptyError'] ?? '',
            $errors['lastNameEmptyError'] ?? '',
            $errors['emailError'] ?? '',
            $errors['passwordError'] ?? ''
        );
    }

    public function getErrorsData(ErrorsDTO $errorsDTO): array
    {
        return [
            'firstNameEmptyError' => $errorsDTO->firstNameEmptyError,
            'lastNameEmptyError' => $errorsDTO->lastNameEmptyError,
            'emailError' => $errorsDTO->emailError,
            'passwordError' => $errorsDTO->passwordError,
        ];
    }
}