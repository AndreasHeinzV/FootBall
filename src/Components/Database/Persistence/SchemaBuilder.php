<?php

declare(strict_types=1);

namespace App\Components\Database\Persistence;

use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\UserFavorite\Persistence\Mapper\FavoriteMapper;
use App\Components\UserFavorite\Persistence\UserFavoriteEntityManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;


readonly class SchemaBuilder
{


    private SchemaTool $schemaTool;

    private EntityManager $entityManager;

    public function __construct(private ORMSqlConnector $ormSqlConnector)
    {
        $this->entityManager = $this->ormSqlConnector->getEntityManager();
        $this->schemaTool = new SchemaTool($this->entityManager);
    }

    public function createSchema(): void
    {
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        $connection = $this->entityManager->getConnection();
        $schemaManager = $connection->createSchemaManager();

        $tablesToCreate = array_map(function ($classMetadata) {
            return $classMetadata->getTableName();
        }, $metadata);

        $existingTables = $schemaManager->listTableNames();
        $needToCreateSchema = array_diff($tablesToCreate, $existingTables);

        if (!empty($needToCreateSchema)) {
            $this->schemaTool->createSchema($metadata);
        }
    }

    public function clearDatabase(): void
    {
        $entityManager = $this->entityManager;
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
      //  dump($metadata);
        // Disable foreign key checks to prevent issues with truncating tables with foreign keys
        $entityManager->getConnection()->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');

        foreach ($metadata as $entityMetadata) {
            $tableName = $entityMetadata->getTableName();

            // Truncate each table. No need for CASCADE in MySQL and no need for a transaction here.
            $entityManager->getConnection()->executeQuery('TRUNCATE TABLE ' . $tableName . ';');
        }

        // Re-enable foreign key checks
        $entityManager->getConnection()->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');
    }

    /*
    public function dropSchema(): void
    {
        $classes = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $this->schemaTool->dropSchema($classes);
    }
*/
    public function fillTables(UserDTO $userDto): void
    {
        $filePath = __DIR__ . '/../../../../tests/Fixtures/ApiRequest/cache/testData.json';
        $jsonData = file_get_contents($filePath);
        $dbData = json_decode($jsonData, true, 512, JSON_THROW_ON_ERROR);
        $this->loadUserIntoDatabase($userDto);

        foreach ($dbData as $data) {
            $result = (new FavoriteMapper())->createFavoriteDTO($data);
            (new UserFavoriteEntityManager($this->ormSqlConnector))->saveUserFavorite($userDto, $result);
        }
    }

    private function loadUserIntoDatabase(UserDTO $userDto): void
    {
        (new UserEntityManager($this->ormSqlConnector))->saveUser($userDto);
    }


}