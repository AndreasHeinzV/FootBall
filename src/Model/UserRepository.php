<?php

namespace App\Model;

class UserRepository
{
    public
    function getUserName(
        $existingUsers,
        $email
    ) {
        foreach ($existingUsers as $existingUser) {
            if ($existingUser['email'] === $email) {
                // echo  "Method: " . $existingUser['firstName']. "<br>";
                return $existingUser['firstName'];
            }
        }
        return '';
    }

    function getUsers($filePath): array
    {
        return file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];
    }
}