<?php

declare(strict_types=1);

namespace App\Core;

use App\Model\DTOs\ErrorsDTO;
use App\Model\DTOs\UserDTO;
use App\Model\Mapper\ErrorMapper;

class Validation implements ValidationInterface
{


    public function checkForNoErrors(ErrorsDTO $errorsDTO): bool
    {
        foreach ($errorsDTO as $key => $error) {
            if (!($error === '')) {
                return false;
            }
        }
        return true;
    }
    public function checkDuplicateMail(array $existingUsers, string $mailToCheck): bool
    {
        foreach ($existingUsers as $user) {
            if (isset($user['email']) && $user['email'] === $mailToCheck) {
                return true;
            }
        }
        return false;
    }

    public function userRegisterValidation(UserDTO $userDTO): ErrorsDTO
    {
        $errors = [];
        if (empty($userDTO->firstName)) {
            $errors['firstNameEmptyError'] = "First name is empty.";
        }

        if (empty($userDTO->lastName)) {
            $errors['lastNameEmptyError'] = "Last name is empty.";
        }

        if (empty($userDTO->email)) {
            $errors['emailEmptyError'] = "Email is empty.";
        } elseif (!filter_var($userDTO->email, FILTER_VALIDATE_EMAIL)) {
            $errors['emailError'] = "Invalid email address.";
        }

        if (empty($userDTO->password)) {
            $errors['passwordEmptyError'] = "Password is empty.";
        } elseif (!preg_match('/^(?=.*\d)(?=.*[!?*#@$%^&])(?=.*[A-Z])(?=.*[a-z]).{7,}$/', $userDTO->password)) {
            $errors['passwordError'] = "Password must be at least 7 characters long and include at least one lowercase letter, 
        one uppercase letter, one number, and one special character like ?!*$#@%^&.";
        }
        return (new ErrorMapper())->createErrorDTO($errors);
    }

    private function checkLoginData(array $existingUsers, string $emailToCheck, string $passwordToCheck): bool
    {
        foreach ($existingUsers as $user) {
            if (isset($user['password'], $user['email']) && $user['email'] === $emailToCheck) {
                return password_verify($passwordToCheck, $user['password']);
            }
        }
        return false;
    }
}