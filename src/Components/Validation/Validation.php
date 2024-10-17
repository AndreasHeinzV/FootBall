<?php

declare(strict_types=1);

namespace App\Components\Validation;

use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\User\Persistence\Mapper\ErrorMapper;

readonly class Validation implements ValidationInterface
{
    public function __construct(private UserBusinessFacadeInterface $userBusinessFacade)
    {
    }

    public function validateNoErrors(ErrorsDTO $errorsDTO): bool
    {
        foreach ($errorsDTO as $error) {
            if (!($error === '')) {
                return false;
            }
        }
        return true;
    }

    public function userRegisterGetErrors(UserDTO $userDTO): ErrorsDTO
    {
        $errors = [];
        $errors['firstNameEmptyError'] = $this->checkForEmptyFirstName($userDTO->firstName);
        $errors['lastNameEmptyError'] = $this->checkForEmptyLastName($userDTO->lastName);
        $errors['emailError'] = $this->validateEmail($userDTO->email);
        $errors['passwordError'] = $this->validatePasswordRegister($userDTO->password);


        return (new ErrorMapper())->createErrorDTO($errors);
    }

    public function userLoginGetErrorsDTO(UserDTO $userDTO): ErrorsDTO
    {
        $errors = [];
        $errors['emailError'] = $this->validateEmail($userDTO->email);
        $errors['passwordError'] = $this->validatePasswordLogin($userDTO);

        return (new ErrorMapper())->createErrorDTO($errors);
    }

    private function validateLogin(UserDTO $userDTO): bool
    {
        $userDTOFromDB = $this->userBusinessFacade->getUserByMail($userDTO->email);
        return password_verify($userDTO->password, $userDTOFromDB->password);
    }


    private function checkForEmptyFirstName(string $firstName): string
    {
        if (empty($firstName)) {
            return "First name is empty.";
        }
        return "";
    }

    private function checkForEmptyLastName(string $lastName): string
    {
        if (empty($lastName)) {
            return "Last name is empty.";
        }
        return "";
    }

    private function validateEmail(string $email): string
    {
        if (empty($email)) {
            return "Email is empty.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email address.";
        }
        return "";
    }

    private function validatePasswordRegister(string $password): string
    {
        if (empty($password)) {
            return "Password is empty.";
        }

        if (!preg_match('/^(?=.*\d)(?=.*[!?*#@$%^&])(?=.*[A-Z])(?=.*[a-z]).{7,}$/', $password)) {
            return "Password must be at least 7 characters long and include at least one lowercase letter, 
        one uppercase letter, one number, and one special character like ?!*$#@%^&.";
        }
        return "";
    }

    private function validatePasswordLogin($userDTO): string
    {
        if (empty($userDTO->password)) {
            return "Password is empty.";
        }

        if (!$this->validateLogin($userDTO)) {
            return 'email or password is wrong';
        }
        return "";
    }
}