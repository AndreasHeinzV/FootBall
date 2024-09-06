<?php

declare(strict_types=1);

namespace App\Model;

use App\Core\ValidationInterface;
use App\Model\DTOs\CompetitionDTO;
use App\Model\DTOs\UserDTO;
use App\Model\Mapper\UserMapper;
use App\Model\Mapper\UserMapperInterface;

class UserEntityManager
{
    private ValidationInterface $validation;
    private UserRepositoryInterface $repository;
    private UserMapper $userMapper;

    public function __construct(
        ValidationInterface $validation,
        UserRepositoryInterface $repository,
        UserMapperInterface $userMapper
    ) {
        $this->validation = $validation;
        $this->repository = $repository;
        $this->userMapper = $userMapper;
    }


    public function save(UserDTO $userData): void
    {
        $existingUsers = $this->repository->getUsers();
        if ($this->validation->checkDuplicateMail($existingUsers, $userData->email)) {
            $user = $this->userMapper->getUserData($userData);
            $this->updateUser($existingUsers, $user);
        } else {
            $existingUsers[] = $userData;
            $this->putUserIntoJson($existingUsers);
        }
    }

    public function saveUserFavorites(UserDTO $userDTO, CompetitionDTO $competitionDTO): void
    {
        $favorites = $this->repository->getFavorites();
        if (!isset($favorites[$userDTO->email])) {
            $favorites[$userDTO->email] = [];
        }
        $favorites[$userDTO->email][] = $competitionDTO;
        $this->putFavIntoJson($favorites);
    }

    private function putFavIntoJson(array $favorites): void
    {
        $path = $this->repository->getFavFilePath();
        file_put_contents($path, json_encode($favorites, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
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