<?php

declare(strict_types=1);

namespace App\Core;

class Validation implements ValidationInterface
{

    private function checkLoginData(array $existingUsers, string $emailToCheck, string $passwordToCheck): bool
    {
        foreach ($existingUsers as $user) {
            if (isset($user['password'], $user['email']) && $user['email'] === $emailToCheck) {
                return password_verify($passwordToCheck, $user['password']);
            }
        }
        return false;
    }

    public function checkPasswordLength(string $value): bool
    {
        return strlen($value) < 7;
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

    public function checkForErrors(array $data): array
    {
        $errors = [];
        if (empty($data['firstName'])) {
            $errors['firstNameEmptyError'] = "First name is empty.";
        }

        if (empty($data['lastName'])) {
            $errors['lastNameEmptyError'] = "Last name is empty.";
        }

        if (!empty($data['email'])) {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['emailError'] = "Invalid email address.";
            }
        } else {
            $errors['emailEmptyError'] = "Email is required.";
        }


        if (empty($errors['password'])) {
            $errors['passwordEmptyError'] = "Password is empty.";
        } else {
            if ($this->checkPasswordLength($data['password'])) {
                $errors['passwordLengthError'] = "Password needs to be at least 7 characters long.";
            }
            if (!preg_match('/[0-9]/', $data['password'])) {
                $errors['passwordNumberError'] = "Password must include at least one number.";
            }
            if (!preg_match('/[!?*#@$%^&]/', $data['password'])) {
                $errors['passwordSpecialError'] = "Password must include at least one special character like ?!*$#@%^&.";
            }
            if (!preg_match('/[A-Z]/', $data['password'])) {
                $errors['passwordUpperError'] = "Password must include at least one uppercase letter.";
            }
            if (!preg_match('/[a-z]/', $data['password'])) {
                $errors['passwordLowerError'] = "Password must include at least one lowercase letter.";
            }
        }
        return $errors;
    }
}