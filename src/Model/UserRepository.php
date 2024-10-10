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

    public function getUserName(array $existingUsers, string $email): string
    {
        foreach ($existingUsers as $existingUser) {
            if ($existingUser['email'] === $email) {
                return $existingUser['firstName'];
            }
        }
        return '';
    }

    public function getUser(array $existingUsers, string $email): UserDTO
    {
        $userMapper = new UserMapper();
        foreach ($existingUsers as $existingUser) {
            if ($existingUser['email'] === $email) {
                return $userMapper->createDTO($existingUser);
            }
        }
        return new UserDTO('', '', '', '');
    }

    public function getUsers(): array

    {
        return $this->sqlConnector->querySelectAll('SELECT * FROM `users`', []);

        //return file_exists($this->filePath) ? json_decode(file_get_contents($this->filePath), true) : [];
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
       // $favorites = $this->getFavorites();
        //return $favorites[$userDTO->email] ?? [];
        return $this->sqlConnector->querySelectAll('SELECT * FROM `favorites` WHERE ', []);
    }

    public function getFavorites(): array
    {
        /*
        $favoritesData = file_exists($this->favFilePath) ? file_get_contents($this->favFilePath) : '';
        return $favoritesData !== '' ? json_decode($favoritesData, true) : [];
*/
        return $this->sqlConnector->querySelectAll('SELECT * FROM `favorites`', []);
    }
}