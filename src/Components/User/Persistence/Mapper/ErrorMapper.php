<?php

declare(strict_types=1);

namespace App\Components\User\Persistence\Mapper;

use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\UserLogin\Persistence\DTO\UserLoginDto;

class ErrorMapper implements ErrorMapperInterface
{
    public function arrayToDto(array $errors): ErrorsDTO
    {
        return new ErrorsDTO(
            $errors['firstNameEmptyError'] ?? null,
            $errors['lastNameEmptyError'] ?? null,
            $errors['emailError'],
            $errors['passwordError']
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

    public function EmptyErrorDto(): ErrorsDTO
    {
        return new ErrorsDTO(null, null, null, null);
    }
}