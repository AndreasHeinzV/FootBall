<?php

declare(strict_types=1);

namespace App\Components\UserRegister\Business\Model;

use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\User\Persistence\Mapper\ErrorMapper;
use App\Components\User\Persistence\Mapper\ErrorMapperInterface;
use App\Components\UserRegister\Business\Model\ValidationTypesRegister\EmailValidation;
use App\Components\UserRegister\Business\Model\ValidationTypesRegister\FirstNameValidation;
use App\Components\UserRegister\Business\Model\ValidationTypesRegister\LastNameValidation;
use App\Components\UserRegister\Business\Model\ValidationTypesRegister\PasswordValidation;
use App\Components\UserRegister\Business\Model\ValidationTypesRegister\ValidationInterface;
use App\Components\UserRegister\Persistence\DTO\UserRegisterDto;

readonly class UserRegisterValidation implements UserRegisterValidationInterface
{
    public function __construct(
        private ErrorMapperInterface $errorMapper,
        private FirstNameValidation $FirstNameValidation,
        private LastNameValidation $LastNameValidation,
        private EmailValidation $EmailValidation,
        private PasswordValidation $PasswordValidation,

    ) {
    }

    public function userRegisterGetErrorsDTO(UserRegisterDto $userRegisterDto): ErrorsDTO
    {
        $errors['firstNameEmptyError'] = $this->FirstNameValidation->validateInput($userRegisterDto->firstName);
        $errors['lastNameEmptyError'] = $this->LastNameValidation->validateInput($userRegisterDto->lastName);
        $errors['emailError'] = $this->EmailValidation->validateInput($userRegisterDto->email);
        $errors['passwordError'] = $this->PasswordValidation->validateInput($userRegisterDto->password);

        return $this->errorMapper->arrayToDto($errors);
    }

    public function validateNoErrors(ErrorsDTO $errorsDTO): bool
    {
        foreach ($errorsDTO as $error) {
            if ($error !== null) {
                return false;
            }
        }
        return true;
    }
}