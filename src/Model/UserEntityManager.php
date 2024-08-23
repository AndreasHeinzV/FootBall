<?php

declare(strict_types=1);

namespace App\Model;

use App\Core\ValidationInterface;

class UserEntityManager
{
    private ValidationInterface $validation;
    private UserRepositoryInterface $repository;
    private array $user;

    public function __construct(ValidationInterface $validation, UserRepositoryInterface $repository)
    {
        $this->validation = $validation;#
        $this->repository = $repository;
    }


    public function save(array $userData): void
    {
        $existingUsers = $this->repository->getUsers();
       // print_r($existingUsers);
        //echo " <-array -> mail: ";
        //print_r($userData['email']);
        if ($this->validation->checkDuplicateMail($existingUsers, $userData['email'])) {

            $this->updateUser( $existingUsers, $userData);

        } else {
          //  echo "Vali doesnt work";
            $existingUsers[] = $userData;
            $this->putUserIntoJson($existingUsers);
        }
    }
    private function updateUser(array $existingUsers, array $user): void
    {
        foreach ($existingUsers as $position => $existingUser) {
            if ($existingUser['email'] === $user['email']) {
                $existingUsers[$position]['firstName'] = $user['firstName'];
                $existingUsers[$position]['lastName'] = $user['lastName'];
                $existingUsers[$position]['password'] = $user['password'];
            }
        }
        $this->putUserIntoJson($existingUsers);
    }

    private function putUserIntoJson(array $existingUsers): void
    {
      //  print_r($existingUsers);
        $path = $this->repository->getFilePath();

        file_put_contents($path, json_encode($existingUsers, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
    }
}