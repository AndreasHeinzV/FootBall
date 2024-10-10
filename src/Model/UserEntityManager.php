<?php

declare(strict_types=1);

namespace App\Model;

use App\Core\SqlConnector;
use App\Model\DTOs\UserDTO;
use App\Model\Mapper\UserMapperInterface;

readonly class UserEntityManager
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private UserMapperInterface $userMapper,
        private SqlConnector $sqlConnector
    ) {
    }


    public function save(UserDTO $userData): void
    {
        $userId = $this->sqlConnector->querySelect(
            'SELECT user_id from users WHERE user_email =:user_email',
            ['user_email' => $userData->email]
        );
        if (!$userId) {
            $this->sqlConnector->queryInsert(
                'INSERT INTO users(user_email,password, first_name, last_name) VALUES(:user_email,:password,:first_name,:last_name)',
                [
                    'user_email' => $userData->email,
                    'password' => $userData->password,
                    'first_name' => $userData->firstName,
                    'last_name' => $userData->lastName,
                ]
            );
        } else {
            $this->updateUser($userId['user_id'], $userData);
        }
    }

    public function saveUserFavorites(UserDTO $userDTO, array $teamData): void
    {
/*
        $favorites = $this->repository->getFavorites();
        if (!isset($favorites[$userDTO->email])) {
            $favorites[$userDTO->email] = [];
        }
        $favorites[$userDTO->email][] = $teamData;
        $this->putFavIntoJson($favorites);
*/


    }

    private function putFavIntoJson(array $favorites): void
    {
        $path = $this->repository->getFavFilePath();
        file_put_contents($path, json_encode($favorites, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
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


    public function saveFavorites(array $favorites): void
    {
        $this->putFavIntoJson($favorites);
    }

}