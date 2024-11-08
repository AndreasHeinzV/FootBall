<?php

declare(strict_types=1);

namespace App\Components\Database\Persistence;

use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;

readonly class SchemaBuilder
{


    public function __construct(private ORMSqlConnector $ormSqlConnector)
    {
    }

    public function createSchema(): string
    {
        $entityManager = $this->ormSqlConnector->getEntityManager();
        $schemaTool = new SchemaTool($entityManager);
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

        $updateSql = $schemaTool->getUpdateSchemaSql($metadata);
        if (empty($updateSql)) {
            return 'Schema already exists.\n';
        }

        try {
            $schemaTool->createSchema($metadata);
            return 'successfully created schema.\n';
        } catch (ToolsException $exception) {
            return 'failed to create schema: ' . $exception->getMessage() . '\n';
        }
    }

}