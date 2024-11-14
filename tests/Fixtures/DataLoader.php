<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\Database\Persistence\SqlConnector;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\UserFavorite\Persistence\Mapper\FavoriteMapper;
use App\Components\UserFavorite\Persistence\UserFavoriteEntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

class DataLoader
{
    public function putDataIntoJson(array $data): void
    {
        $filePath = __DIR__ . '/ApiRequest/cache/testData.json';
        $jsonContent = json_encode($data, JSON_PRETTY_PRINT);
        $result = file_put_contents($filePath, $jsonContent);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws \JsonException
     */
    public function loadTestDataIntoDatabase(UserDTO $userDTO): void
    {
        $filePath = __DIR__ . '/ApiRequest/cache/testData.json';
        $jsonData = file_get_contents($filePath);
        $dbData = json_decode($jsonData, true, 512, JSON_THROW_ON_ERROR);


        foreach ($dbData as $data) {


            $result = (new FavoriteMapper())->createFavoriteDTO($data);

            (new UserFavoriteEntityManager((new ORMSqlConnector())))->saveUserFavorite($userDTO, $result);
        }
    }
}