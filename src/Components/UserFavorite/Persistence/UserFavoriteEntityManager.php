<?php

declare(strict_types=1);

namespace App\Components\UserFavorite\Persistence;

use App\Components\Database\Persistence\Entity\FavoriteEntity;
use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\UserFavorite\Persistence\DTO\FavoriteDTO;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

readonly class UserFavoriteEntityManager implements UserFavoriteEntityManagerInterface
{
    private EntityManager $entityManager;


    public function __construct(
        private ORMSqlConnector $sqlConnector
    ) {
        $this->entityManager = $this->sqlConnector->getEntityManager();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function saveUserFavorite(UserDTO $userDTO, FavoriteDTO $favoriteDTO): void
    {
        $favorite = new FavoriteEntity();
        $favorite->setFavoritePosition($favoriteDTO->position);
        $favorite->setTeamCrest($favoriteDTO->crest);
        $favorite->setTeamName($favoriteDTO->teamName);
        $favorite->setTeamId($favoriteDTO->teamID);
        $favorite->setUserIdFk($userDTO->userId);

        $this->entityManager->persist($favorite);
        $this->entityManager->flush();
    }

    public function updateUserFavoritePosition(
        FavoriteEntity $favoriteEntity,
        FavoriteEntity $favoriteEntityChange,
        int $position,
        int $positionToChange,

    ): void {
        $favoriteEntity->setFavoritePosition($positionToChange);
        $favoriteEntityChange->setFavoritePosition($position);
        $this->entityManager->persist($favoriteEntity);
        $this->entityManager->persist($favoriteEntityChange);
        $this->entityManager->flush();
    }

    /**
     * @throws ORMException
     */
    public function deleteUserFavorite(UserDTO $userDTO, string $id): void
    {
        $favoriteEntity = $this->entityManager->getRepository(FavoriteEntity::class)->findOneBy(
            ['userIdFk' => $userDTO->userId, 'teamId' => $id]
        );

        if ($favoriteEntity !== null) {
            $this->entityManager->remove($favoriteEntity);
        }
    }
}