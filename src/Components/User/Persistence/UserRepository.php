<?php

declare(strict_types=1);

namespace App\Components\User\Persistence;

use App\Components\Database\Persistence\SqlConnectorInterface;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\User\Persistence\Mapper\UserMapper;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(public SqlConnectorInterface $sqlConnector)
    {
    }
    public function getUser(string $email): UserDTO
    {
        $user = $this->sqlConnector->querySelect(
            'SELECT user_id, user_email, first_name, last_name, password FROM users WHERE user_email = :user_email',
            ['user_email' => $email]
        );
        $userMapper = new UserMapper();
        if (!$user) {
            return new UserDTO(null,'', '', '', '');
        }

        return $userMapper->createDTO([
            'userId' => $user["user_id"],
            'firstName' => $user["first_name"],
            'lastName' => $user["last_name"],
            'email' => $user["user_email"],
            'password' => $user["password"],
        ]);
    }

    public function getUsers(): array
    {
        return $this->sqlConnector->querySelectAll('SELECT * FROM users');
    }

    public function getUserIdByMail(UserDTO $userDTO): int|false
    {
        $userID = $this->sqlConnector->querySelect(
            'SELECT user_id FROM users WHERE user_email = :user_email',
            ['user_email' => $userDTO->email]
        );

        return $userID['user_id'] ?? false;
    }



}