<?php

declare(strict_types=1);

namespace App\Components\User\Persistence;

use App\Components\Database\Persistence\SqlConnectorInterface;
use App\Components\User\Persistence\DTOs\UserDTO;

readonly class UserEntityManager implements UserEntityManagerInterface
{
    public function __construct(
        private SqlConnectorInterface $sqlConnector
    ) {
    }


    public function saveUser(UserDTO $userDTO): void
    {
        $userId = $this->sqlConnector->querySelect(
            'SELECT user_id from users WHERE user_email =:user_email',
            ['user_email' => $userDTO->email]
        );
        if (!$userId) {
            $this->sqlConnector->queryInsert(
                'INSERT INTO users(user_email,password, first_name, last_name) VALUES(:user_email,:password,:first_name,:last_name)',
                [
                    'user_email' => $userDTO->email,
                    'password' => $userDTO->password,
                    'first_name' => $userDTO->firstName,
                    'last_name' => $userDTO->lastName,
                ]
            );
        } else {
            $this->updateUser($userId['user_id'], $userDTO);
        }
    }


    private function updateUser(int $userId, userDTO $user): void
    {
        $this->sqlConnector->queryInsert(
            'UPDATE users SET user_email= :user_email, first_name = :first_name, last_name =:last_name WHERE user_id = :user_id',
            [
                'user_email' => $user->email,
                'first_name' => $user->firstName,
                'last_name' => $user->lastName,
                'user_id' => $userId,
            ]
        );
    }
}