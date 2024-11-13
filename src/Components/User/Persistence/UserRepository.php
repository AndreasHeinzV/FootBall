<?php

declare(strict_types=1);

namespace App\Components\User\Persistence;

use App\Components\Database\Persistence\Entity\UserEntity;
use App\Components\Database\Persistence\Mapper\UserEntityMapper;
use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\User\Persistence\DTOs\UserDTO;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;

readonly class UserRepository implements UserRepositoryInterface
{
    private EntityManager $entityManager;

    public function __construct
    (

        private ORMSqlConnector $ORMSqlConnector,
        private UserEntityMapper $userEntityMapper,
    ) {
        $this->entityManager = $this->ORMSqlConnector->getEntityManager();
    }

    public function getUser(string $email): UserDTO
    {
        $user = $this->entityManager->getRepository(UserEntity::class)->findOneBy(['email' => $email]);

        if ($user instanceof UserEntity) {
            return $this->userEntityMapper->mapUserEntityToUserDto($user);
        }
        return new UserDTO(null, '', '', '', '');
    }

    public function getUsers(): array
    {
        $users = $this->entityManager->getRepository(UserEntity::class)->findAll();

        if (!$users) {
            throw new EntityNotFoundException('No users found.');
        }
        return $users;
    }

    public function getUserIdByMail(UserDTO $userDTO): int|false
    {
        $user = $this->entityManager->getRepository(UserEntity::class)->findOneBy(['email' => $userDTO->email]);

        if ($user instanceof UserEntity) {
            return $user->getId();
        }
        return false;
    }
}