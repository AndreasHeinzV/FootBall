<?php

declare(strict_types=1);

namespace App\Components\UserFavorite\Persistence;

use App\Components\Database\Persistence\SqlConnectorInterface;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\UserFavorite\Persistence\DTO\FavoriteDTO;

readonly class UserFavoriteEntityManager implements UserFavoriteEntityManagerInterface
{
    public function __construct(
        private SqlConnectorInterface $sqlConnector
    ) {
    }

    public function saveUserFavorite(UserDTO $userDTO, FavoriteDTO $favoriteDTO): void
    {
        $this->sqlConnector->queryInsert(
            'INSERT INTO favorites(user_id,team_id, team_name, team_crest ) VALUES (:user_id,:team_id,:team_name,:team_crest)',
            [
                'user_id' => $userDTO->userId,
                'team_id' => $favoriteDTO->teamID,
                'team_name' => $favoriteDTO->teamName,
                'team_crest' => $favoriteDTO->crest,
            ]
        );
    }

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

    public function deleteUserFavorite(UserDTO $userDTO, string $id): void
    {
        $this->sqlConnector->queryManipulate(
            '
       DELETE FROM favorites where team_id = :team_id and user_id = :user_id',
            [
                'team_id' => (int)$id,
                'user_id' => $userDTO->userId,
            ]
        );
    }
}