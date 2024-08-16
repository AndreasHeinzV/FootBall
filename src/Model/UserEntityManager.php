<?php

declare(strict_types=1);

namespace App\Model;

use App\Core\Validation;

class UserEntityManager
{

    private function safeUser( array $existingUsers): void
    {
        file_put_contents(__DIR__. '/../../users.json', json_encode($existingUsers, JSON_PRETTY_PRINT));
    }

    private function updateUser(array &$existingUsers, array $user, string $email): void
    {
        foreach ($existingUsers as $position => &$existingUser) {
            if ($existingUser['email'] === $email) {
                $existingUsers[$position]['firstName'] = $user['firstName'];
                $existingUsers[$position]['lastName'] = $user['lastName'];
                $existingUsers[$position]['password'] = $user['password'];
                $this->safeUser($existingUsers);

            }
        }
    }

    public function save(array $user, string $email, string $filePath): void
    {

         $validation = new Validation();
         $repository = new UserRepository();
        $existingUsers = $repository->getUsers($filePath);

        if ($validation->checkDuplicateMail($existingUsers, $email)) {
            $this->updateUser($existingUsers, $user, $email);
        } else {
            $existingUsers[] = $user;
            $this->safeUser( $existingUsers);
        }
    }
}