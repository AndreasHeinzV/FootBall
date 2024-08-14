<?php

namespace App\Core;

class Validation
{
    public function validateMail($email): bool
    {
        return true;
    }

    public function checkEmail($email): bool{

        return true;
    }

    private
    function checkLoginData(
        $existingUsers,
        $emailToCheck,
        $passwordToCheck
    ): bool {
        foreach ($existingUsers as $user) {
            if (isset($user['password']) && isset($user['email'])) {
                if ($user['email'] === $emailToCheck) {
                    return password_verify($passwordToCheck, $user['password']);
                }
            }
        }
        return false;
    }
}