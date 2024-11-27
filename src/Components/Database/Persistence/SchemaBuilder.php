<?php

declare(strict_types=1);

namespace App\Components\Database\Persistence;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;

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

        $tablesToCreate = array_map(function($classMetadata) {
            return $classMetadata->getTableName();
        }, $metadata);

        $existingTables = $schemaManager->listTableNames();
        $needToCreateSchema = array_diff($tablesToCreate, $existingTables);

        if (!empty($needToCreateSchema)) {
            $this->schemaTool->createSchema($metadata);
        }
    }


    public function dropSchema(): void
    {
        $classes = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $this->schemaTool->dropSchema($classes);
    }


}