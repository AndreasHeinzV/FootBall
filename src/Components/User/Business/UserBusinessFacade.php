<?php

declare(strict_types=1);

namespace App\Components\User\Business;

use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepositoryInterface;


readonly class UserBusinessFacade implements UserBusinessFacadeInterface
{

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserEntityManager $userEntityManager
    ) {
    }

    public function getUserByMail(string $email): UserDTO
    {
        return $this->userRepository->getUser($email);
    }

    public function getUserById(UserDTO $userDTO): int
    {
        return $this->userRepository->getUserID($userDTO);
    }

    public function getUsers(): array
    {
        return $this->userRepository->getUsers();
    }

    public function registerUser(UserDTO $userDTO): void
    {
         $this->userEntityManager->saveUser($userDTO);
    }

}