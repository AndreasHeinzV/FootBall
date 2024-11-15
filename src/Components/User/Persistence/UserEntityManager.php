<?php

declare(strict_types=1);

namespace App\Components\User\Persistence;

use App\Components\Database\Persistence\Entity\UserEntity;
use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\User\Persistence\DTOs\UserDTO;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

readonly class UserEntityManager implements UserEntityManagerInterface
{
    private EntityManager $entityManager;

    public function __construct(
        private ORMSqlConnector $sqlConnectorNew,
    ) {
        $this->entityManager = $this->sqlConnectorNew->getEntityManager();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function saveUser(UserDTO $userDTO): void
    {
        $userRepository = $this->entityManager->getRepository(UserEntity::class);
        $user = $userRepository->findOneBy(['email' => $userDTO->email]);

        if ($user === null) {
            $user = new UserEntity();
            $user->setEmail($userDTO->email);
        }
        $user->setPassword($userDTO->password);
        $user->setFirstName($userDTO->firstName);
        $user->setLastName($userDTO->lastName);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function updateUserPassword(UserDTO $userDTO): void
    {
        $userRepository = $this->entityManager->getRepository(UserEntity::class);
        $userEntity = $userRepository->findOneBy(['id' => $userDTO->userId]);


        if ($userEntity !== null) {
            $userEntity->setPassword($userDTO->password);
            $this->entityManager->persist($userEntity);
            $this->entityManager->flush();
        }
    }


    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    /*
    private function updateUser(int $userId, userDTO $userDTO): void
    {
        $userRepository = $this->entityManager->getRepository(UserEntity::class);
        $userEntity = $userRepository->findOneBy(['id' => $userId]);

        if ($userEntity !== null) {
            $userEntity->setPassword($userDTO->password);
            $this->entityManager->persist($userEntity);
            $this->entityManager->flush();
        }
    }
*/
}