<?php

declare(strict_types=1);

namespace App\Components\UserLogin\Business\Model;

use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\User\Persistence\Mapper\ErrorMapper;
use App\Components\User\Persistence\Mapper\ErrorMapperInterface;
use App\Components\User\Persistence\Mapper\UserMapperInterface;
use App\Components\UserLogin\Business\Model\ValidationTypesLogin\EmailLoginValidation;
use App\Components\UserLogin\Business\Model\ValidationTypesLogin\PasswordLoginValidation;
use App\Components\UserLogin\Persistence\DTO\UserLoginDto;

readonly class UserLoginValidation implements UserLoginValidationInterface
{
    public function __construct(
        private ErrorMapperInterface $errorMapper,
        private EmailLoginValidation $emailLoginValidation,
        private PasswordLoginValidation $passwordLoginValidation,
    ) {
    }

    public function userLoginGetErrorsDTO(UserLoginDto $userLoginDto): ErrorsDTO
    {
        $errorsDTO = $this->errorMapper->emptyErrorDto();
        $errorsDTO->emailError = $this->emailLoginValidation->validateInput($userLoginDto);
        $errorsDTO->passwordError = $this->passwordLoginValidation->validateInput($userLoginDto);
        return $errorsDTO;
    }

    public function validateNoErrors(ErrorsDTO $errorsDTO): bool
    {
        foreach (get_object_vars($errorsDTO) as $error) {
            if ($error !== null) {
                return false;
            }
        }
        return true;
    }
}