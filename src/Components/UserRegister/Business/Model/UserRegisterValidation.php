<?php

declare(strict_types=1);

namespace App\Components\UserRegister\Business\Model;

use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\User\Persistence\Mapper\ErrorMapper;
use App\Components\User\Persistence\Mapper\ErrorMapperInterface;
use App\Components\UserRegister\Persistence\DTO\UserRegisterDto;

readonly class UserRegisterValidation implements UserRegisterValidationInterface
{
    public function __construct(private ErrorMapperInterface $errorMapper)
    {
    }

    public function userRegisterGetErrorsDTO(UserRegisterDto $userRegisterDto): ErrorsDTO
    {
        $errors['firstNameEmptyError'] = $this->checkForEmptyFirstName($userRegisterDto->firstName);
        $errors['lastNameEmptyError'] = $this->checkForEmptyLastName($userRegisterDto->lastName);
        $errors['emailError'] = $this->validateEmail($userRegisterDto->email);
        $errors['passwordError'] = $this->validatePasswordRegister($userRegisterDto->password);

        return $this->errorMapper->arrayToDto($errors);
    }
//todo classes for every validation
    public function validateNoErrors(ErrorsDTO $errorsDTO): bool
    {
        foreach ($errorsDTO as $error) {
            if ($error !== null) {
                return false;
            }
        }
        return true;
    }

    private function validateEmail(string $email): ?string
    {
        if (empty($email)) {
            return "Email is empty.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email address.";
        }
        return null;
    }

    private function validatePasswordRegister(string $password): ?string
    {
        if (empty($password)) {
            return "Password is empty.";
        }

        if (!preg_match('/^(?=.*\d)(?=.*[!?*#@$%^&])(?=.*[A-Z])(?=.*[a-z]).{7,}$/', $password)) {
            return "Password must be at least 7 characters long and include at least one lowercase letter, 
        one uppercase letter, one number, and one special character like ?!*$#@%^&.";
        }
        return null;
    }

    private function checkForEmptyFirstName(string $firstName): ?string
    {
        if (empty($firstName)) {
            return "First name is empty.";
        }
        return null;
    }

    private function checkForEmptyLastName(string $lastName): ?string
    {
        if (empty($lastName)) {
            return "Last name is empty.";
        }
        return null;
    }
}