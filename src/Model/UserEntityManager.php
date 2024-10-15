<?php

declare(strict_types=1);

namespace App\Model;

use App\Core\SqlConnector;
use App\Model\DTOs\FavoriteDTO;
use App\Model\DTOs\UserDTO;
use App\Model\Mapper\UserMapperInterface;

readonly class UserEntityManager
{
    public function __construct(
        private UserRepository $repository,
        private SqlConnector $sqlConnector
    ) {
    }


    public function saveUser(UserDTO $userData): void
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

    public function saveUserFavorite(UserDTO $userDTO, FavoriteDTO $favoriteDTO): void
    {
        $userID = $this->repository->getUserID($userDTO);
        $this->sqlConnector->queryInsert(
            'INSERT INTO favorites(user_id,team_id, team_name, team_crest ) VALUES (:user_id,:team_id,:team_name,:team_crest)',

            [
                'user_id' => $userID,
                'team_id' => $favoriteDTO->teamID,
                'team_name' => $favoriteDTO->teamName,
                'team_crest' => $favoriteDTO->crest,
            ]
        );
    }

    /*
     *
            $favorites = $this->repository->getFavorites();
            if (!isset($favorites[$userDTO->email])) {
                $favorites[$userDTO->email] = [];
            }
            $favorites[$userDTO->email][] = $teamData;
            $this->putFavIntoJson($favorites);
    */

    public function updateUserFavoritePosition(
        int $userID,
        int $currentTeamID,
        int $prevTeamID,
        int $currentPosition,
        int $previousPosition
    ): void {

        $this->sqlConnector->queryManipulate(
            'UPDATE favorites SET favorite_position = -1 WHERE user_id = :user_id AND team_id = :team_id',
            [
                'user_id' => $userID,
                'team_id' => $currentTeamID,
            ]
        );

        $this->sqlConnector->queryManipulate(
            'UPDATE favorites SET favorite_position =:favorite_position WHERE user_id = :user_id AND team_id = :team_id ',
            [
                'favorite_position' => $currentPosition,
                'user_id' => $userID,
                'team_id' => $prevTeamID,
            ]
        );

        $this->sqlConnector->queryManipulate(
            'UPDATE favorites SET favorite_position =:favorite_position WHERE user_id = :user_id AND team_id = :team_id ',
            [
                'favorite_position' => $previousPosition,
                'user_id' => $userID,
                'team_id' => $currentTeamID,
            ]
        );


    }
    public function deleteUserFavorite (UserDTO $userDTO, string $id): void
    {
        $userID = $this->repository->getUserID($userDTO);

        $this->sqlConnector->queryManipulate(
            '
       DELETE FROM favorites where team_id = :team_id and user_id = :user_id',
            [
                'team_id' => (int)$id,
                'user_id' => $userID,
            ]
        );
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