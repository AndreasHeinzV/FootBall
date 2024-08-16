<?php
declare(strict_types=1);
namespace App\Model;

class UserRepository
{
    public function getUserName(array $existingUsers,string $email): string {
        foreach ($existingUsers as $existingUser) {
            if ($existingUser['email'] === $email) {
                // echo  "Method: " . $existingUser['firstName']. "<br>";
                return $existingUser['firstName'];
            }
        }
        return '';
    }

    public function getUser(array $existingUsers,string $email): array{
        foreach ($existingUsers as $existingUser) {
            if ($existingUser['email'] === $email) {
                return $existingUser;
            }
        }
        return [];
    }

    public function getUsers(string $filePath): array

    {
        return file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];
    }

}