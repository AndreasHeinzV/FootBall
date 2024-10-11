<?php

declare(strict_types=1);

namespace App\Model;

use App\Core\SqlConnector;
use App\Model\DTOs\UserDTO;
use App\Model\Mapper\UserMapper;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(public SqlConnector $sqlConnector)
    {
    }

    public function getUserName(string $email): string
    {
        $user = $this->sqlConnector->querySelect(
            'SELECT first_name FROM users WHERE user_email = :user_email',
            ['user_email' => $email]
        );

        if (!$user) {
            return '';
        }

        return $user["first_name"];
    }

    public function getUser(string $email): UserDTO
    {
        $user = $this->sqlConnector->querySelect(
            'SELECT user_email, first_name, last_name, password FROM users WHERE user_email = :user_email',
            ['user_email' => $email]
        );
        $userMapper = new UserMapper();
        if (!$user) {
            return new UserDTO('', '', '', '');
        }

        return $userMapper->createDTO([
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

    public function getUserFavorites(UserDTO $userDTO): array
    {
        $userID = $this->getUserID($userDTO);
        $favoriteArray = $this->sqlConnector->querySelectAll(
            'SELECT favorite_position, team_id, team_name, team_crest FROM favorites WHERE user_id = :user_id',
            ['user_id' => $userID]
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

    public function getUserID(userDTO $userDTO): int|false
    {
        $userID = $this->sqlConnector->querySelect(
            'SELECT user_id FROM users WHERE user_email = :user_email',
            ['user_email' => $userDTO->email]
        );

        return $userID['user_id'] ?? false;
    }
}