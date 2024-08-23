<?php

declare(strict_types=1);

namespace App\Model;

class UserRepository implements UserRepositoryInterface
{
    private string $filePath;

    public function __construct()
    {
        $name = 'users.json';
        if (isset($_ENV['test'])) {
            $name = 'users_test.json';
        }
        $this->filePath = __DIR__ . '/../../' . $name;
    }

    public function getUserName(array $existingUsers, string $email): string
    {
        foreach ($existingUsers as $existingUser) {
            if ($existingUser['email'] === $email) {
                // echo  "Method: " . $existingUser['firstName']. "<br>";
                return $existingUser['firstName'];
            }
        }
        return '';
    }

    public function getUsers(): array

    {
        return file_exists($this->filePath) ? json_decode(file_get_contents($this->filePath), true) : [];
    }


    public function getFilePath(): string
    {
        return $this->filePath;
    }
    /*
        public function getUser(array $existingUsers,string $email): array{
            foreach ($existingUsers as $existingUser) {
                if ($existingUser['email'] === $email) {
                    return $existingUser;
                }
            }
            return [];
        }
    */
}