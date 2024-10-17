<?php

declare(strict_types=1);

namespace App\Components\UserFavorite\Persistence;

use App\Components\Database\Persistence\SqlConnectorInterface;
use App\Components\User\Persistence\DTOs\UserDTO;

class UserFavoriteRepository implements UserFavoriteRepositoryInterface
{

    public function __construct(public SqlConnectorInterface $sqlConnector)
    {
    }

    public function getUserFavorites(UserDTO $userDTO): array
    {

        $favoriteArray = $this->sqlConnector->querySelectAll(
            'SELECT favorite_position, team_id, team_name, team_crest FROM favorites WHERE user_id = :user_id ORDER BY favorite_position ASC ',
            ['user_id' => $userDTO->userId]
        );
        if (!$favoriteArray) {
            return [];
        }
        $returnArray = [];
        foreach ($favoriteArray as $favorite) {
            $returnArray[] = [
                'favoritePosition' => $favorite["favorite_position"],
                'teamName' => $favorite['team_name'],
                'teamID' => $favorite['team_id'],
                'crest' => $favorite['team_crest'],
            ];
        }
        return $returnArray;
    }

    public function checkExistingFavorite(UserDTO $userDTO, string $teamID): bool
    {
        $returnValue = $this->sqlConnector->querySelect(
            'SELECT f.team_id from users u INNER JOIN favorites f ON u.user_id = f.user_id
                 WHERE u.user_email = :user_email AND f.team_id =:team_id',
            ['user_email' => $userDTO->email, 'team_id' => $teamID]
        );
        return $returnValue !== false;
    }

    public function getUserFavoritePosition(UserDTO $userDTO, string $id): int|false
    {

        $favoritePosition = $this->sqlConnector->querySelect(
            'SELECT favorite_position FROM favorites WHERE user_id = :user_id AND team_id = :team_id',
            [
                'user_id' => (int)$userDTO->userId,
                'team_id' => (int)$id
            ]
        );
        return $favoritePosition['favorite_position'] ?? false;
    }

    public function getUserMinFavoritePosition(UserDTO $userDTO): int|false
    {
        {

            $minPosition = $this->sqlConnector->querySelect(
                'SELECT MIN(favorite_position) AS min_position FROM favorites WHERE user_id = :user_id',
                ['user_id' => $userDTO->userId]
            );
            return $minPosition['min_position'] ?? false;
        }
    }
    public function getUserMaxFavoritePosition(UserDTO $userDTO): int|false
    {
        {

            $max = $this->sqlConnector->querySelect(
                'SELECT MAX(favorite_position) AS max_position FROM favorites WHERE user_id = :user_id',
                ['user_id' => $userDTO->userId]
            );
            return $max['max_position'] ?? false;
        }
    }
}