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

        $updateSql = $this->schemaTool->getUpdateSchemaSql($metadata);
        if (empty($updateSql)) {
         return;
        }

        try {
            $this->schemaTool->createSchema($metadata);
        } catch (ToolsException $exception) {

        }
    }

    public function dropSchema(): void{

        $classes = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $this->schemaTool->dropSchema($classes);

    }


}