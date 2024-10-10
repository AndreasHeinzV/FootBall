<?php

declare(strict_types=1);

namespace App\Model;

use App\Core\SqlConnector;
use App\Model\DTOs\UserDTO;
use App\Model\Mapper\UserMapper;

class UserRepository implements UserRepositoryInterface
{
    private string $filePath;
    private string $favFilePath;

    public function __construct(public SqlConnector $sqlConnector)
    {
        $name = 'users.json';
        $fav = 'favorites.json';
        if (isset($_ENV['test'])) {
            $name = 'users_test.json';
            $fav = 'favorites_test.json';
        }
        $this->filePath = __DIR__ . '/../../' . $name;
        $this->favFilePath = __DIR__ . '/../../' . $fav;
    }

    public function getUserName(string $email): string
    {
        $user = $this->sqlConnector->querySelect(
            'SELECT first_name FROM users WHERE email = :email',
            ['email' => $email]
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


    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function getFavFilePath(): string
    {
        return $this->favFilePath;
    }

    public function getUserFavorites(UserDTO $userDTO): array
    {
        $userID = $this->getUserID($userDTO);
        $favoriteArray =$this->sqlConnector->querySelectAll(
            'SELECT favorite_position, team_id, team_name, team_crest FROM favorites WHERE user_id = :user_id',
            ['user_id' => $userID]
        );
        $returnArray = [];
        foreach ($favoriteArray as $favorite) {
            $returnArray[] = [
                'favoritePosition' => $favorite["favorite_position"],
                'teamName' => $favorite['team_name'],
                'teamID' => $favorite['team_id'],
                'crest' => $favorite['team_crest']
            ];
        }
        return $returnArray;
    }

    /*
        public function getFavorites(): array
        {
            /*
            $favoritesData = file_exists($this->favFilePath) ? file_get_contents($this->favFilePath) : '';
            return $favoritesData !== '' ? json_decode($favoritesData, true) : [];

            return $this->sqlConnector->querySelectAll('SELECT * FROM `favorites`', []);
        }
    */
    private function getUserID(userDTO $userDTO): int
    {
        $userID = $this->sqlConnector->querySelect(
            'SELECT user_id FROM users WHERE user_id = :user_id',
            ['user_id' => $userDTO->email]
        );
        return $userID['user_id'];
    }
}