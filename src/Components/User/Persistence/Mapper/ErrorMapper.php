<?php

declare(strict_types=1);

namespace App\Components\User\Persistence\Mapper;

use App\Components\User\Persistence\DTOs\ErrorsDTO;

class ErrorMapper implements ErrorMapperInterface
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

    public function ErrorDTOToArray(ErrorsDTO $errorsDTO): array
    {
        return [
            'firstNameEmptyError' => $errorsDTO->firstNameEmptyError,
            'lastNameEmptyError' => $errorsDTO->lastNameEmptyError,
            'emailError' => $errorsDTO->emailError,
            'passwordError' => $errorsDTO->passwordError,
        ];
    }
}