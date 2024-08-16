<?php

declare(strict_types=1);

namespace App\Core;

class Validation
{
    public function validateMail($email): bool
    {
        return true;
    }

    public function checkEmail($email): bool
    {
        return true;
    }

    private function checkLoginData(array $existingUsers, string $emailToCheck, string $passwordToCheck): bool
    {
        foreach ($existingUsers as $user) {
            if (isset($user['password']) && isset($user['email'])) {
                if ($user['email'] === $emailToCheck) {
                    return password_verify($passwordToCheck, $user['password']);
                }
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

    private function setEmptyError($value){
        return;
    }
}