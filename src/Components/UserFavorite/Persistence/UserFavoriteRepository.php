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
        /*
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
        */

        $userEntity = $this->entityManager->getRepository(UserEntity::class)->find($userDTO->userId);

        if (!$userEntity) {
            return [];
        }

        $favorites = $userEntity->getFavorites();
        $FavoriteDTOArray = [];
        foreach ($favorites as $favorite) {
            $returnValue = [
                'teamName' => $favorite->getTeamName(),
                'teamID' => $favorite->getTeamID(),
                'crest' => $favorite->getTeamCrest(),
                'position' => $favorite->getPosition(),
            ];
            $FavoriteDTOArray[] = $this->mapper->createFavoriteDTO($returnValue);
        }
        return $FavoriteDTOArray;
    }

    public function checkExistingFavorite(UserDTO $userDTO, string $teamID): bool
    {
        $returnValue = $this->entityManager->getRepository(FavoriteEntity::class)->findOneBy(
            ['userIdFk' => $userDTO->userId, 'teamId' => $teamID]
        );
        return $returnValue !== false;
    }


    public function getUserFavoritePosition(UserDTO $userDTO, string $id): int|false
    {
        /*
        $favoritePosition = $this->sqlConnector->querySelect(
            'SELECT favorite_position FROM favorites WHERE user_id = :user_id AND team_id = :team_id',
            [
                'userIdFk' => (int)$userDTO->userId,
                'teamId' => (int)$id,
            ]
        );
        return $favoritePosition['favorite_position'] ?? false;
        */
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