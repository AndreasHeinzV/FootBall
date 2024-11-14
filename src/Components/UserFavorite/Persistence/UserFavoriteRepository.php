<?php

declare(strict_types=1);

namespace App\Components\UserFavorite\Persistence;

use App\Components\Database\Persistence\Entity\FavoriteEntity;
use App\Components\Database\Persistence\Entity\UserEntity;
use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\Database\Persistence\SqlConnectorInterface;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\UserFavorite\Persistence\Mapper\FavoriteMapper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

class UserFavoriteRepository implements UserFavoriteRepositoryInterface
{
    private EntityManager $entityManager;

    public function __construct(public ORMSqlConnector $sqlConnector, private FavoriteMapper $mapper)
    {
        $this->entityManager = $this->sqlConnector->getEntityManager();
    }

    public function findFavoritesByUserOrdered($userId)
    {
        return $this->createQueryBuilder('f')
            ->where('f.user = :user_id')
            ->setParameter('user_id', $userId)
            ->orderBy('f.favoritePosition', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getUserFavorites(UserDTO $userDTO): array
    {
        $userFavorites = $this->entityManager->getRepository(FavoriteEntity::class)->findBy(
            ['userIdFk' => $userDTO->userId], ['favorite_position' => 'ASC']
        );

        if (empty($userFavorites)) {
            return [];
        }

        $FavoriteDTOArray = [];
        foreach ($userFavorites as $favorite) {
            $returnValue = [
                'teamName' => $favorite->getTeamName(),
                'teamID' => $favorite->getTeamId(),
                'crest' => $favorite->getTeamCrest(),
                'favoritePosition' => $favorite->getFavoritePosition(),
            ];
            $FavoriteDTOArray[] = $this->mapper->createFavoriteDTO($returnValue);
        }
        return $FavoriteDTOArray;
    }

    public function getUserFavoriteByTeamId(UserDTO $userDTO, int $teamId): ?FavoriteEntity
    {
        return $this->entityManager->getRepository(FavoriteEntity::class)->findOneBy(
            ['userIdFk' => $userDTO->userId, 'teamId' => $teamId]
        );
    }

    public function checkExistingFavorite(UserDTO $userDTO, string $teamID): bool
    {
        $returnValue = $this->entityManager->getRepository(FavoriteEntity::class)->findOneBy(
            ['userIdFk' => $userDTO->userId, 'teamId' => $teamID]
        );
        return ($returnValue instanceof FavoriteEntity);
    }

    public function getUserFavoriteEntityByPosition(UserDTO $userDTO, int $position): ?FavoriteEntity
    {
        return $this->entityManager->getRepository(FavoriteEntity::class)->findOneBy(
            ['userIdFk' => $userDTO->userId, 'favorite_position' => $position]
        );
    }

    public function getUserFavoritePositionByTeamId(UserDTO $userDTO, string $id): int|false
    {
        $returnValue = $this->entityManager->getRepository(FavoriteEntity::class)->findOneBy(
            ['userIdFk' => $userDTO->userId, 'teamId' => $id]
        );

        return $returnValue->getFavoritePosition() ?? false;
    }

    public function getFavoritePositionAboveCurrentPosition(UserDTO $userDTO, int $position): int|false
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('f')
            ->from(FavoriteEntity::class, 'f')
            ->where('f.userIdFk = :userId')
            ->andWhere('f.favorite_position < :favoritePosition')
            ->setParameter('userId', $userDTO->userId)
            ->setParameter('favoritePosition', $position)
            ->orderBy('f.favorite_position', 'DESC')
            ->setMaxResults(1);

        $result = $qb->getQuery()->getOneOrNullResult();

        if ($result instanceof FavoriteEntity) {
            return $result->getFavoritePosition();
        }
        return false;
    }

    public function getFavoritePositionBelowCurrentPosition(UserDTO $userDTO, int $position): int|false
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('f')
            ->from(FavoriteEntity::class, 'f')
            ->where('f.userIdFk = :userId')
            ->andWhere('f.favorite_position > :favoritePosition')
            ->setParameter('userId', $userDTO->userId)
            ->setParameter('favoritePosition', $position)
            ->orderBy('f.favorite_position', 'ASC')
            ->setMaxResults(1);

        $result = $qb->getQuery()->getOneOrNullResult();

        if ($result instanceof FavoriteEntity) {
            return $result->getFavoritePosition();
        }
        return false;
    }

    public function getUserMinFavoritePosition(UserDTO $userDTO): int|false
    {
        $minPosition = $this->sqlConnector->querySelect(
            'SELECT MIN(favorite_position) AS min_position FROM favorites WHERE user_id = :user_id',
            ['user_id' => $userDTO->userId]
        );
        return $minPosition['min_position'] ?? false;
    }


    public function getUserFavoritesLastPosition(UserDTO $userDTO): int|false
    {
        $lastRow = $this->entityManager->getRepository(FavoriteEntity::class)->findBy(['userIdFk' => $userDTO->userId],
            ['favorite_position' => 'DESC'],
            1);
        return $lastRow[0]->getFavoritePosition() ?? false;
    }


    public function getUserFavoritesFirstPosition(UserDTO $userDTO): int|false
    {
        $firstRow = $this->entityManager->getRepository(FavoriteEntity::class)->findBy(['userIdFk' => $userDTO->userId],
            ['favorite_position' => 'ASC'],
            1);
        return $firstRow[0]->getFavoritePosition() ?? false;
    }
}