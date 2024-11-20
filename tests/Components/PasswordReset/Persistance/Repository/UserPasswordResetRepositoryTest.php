<?php

declare(strict_types=1);

namespace App\Tests\Components\PasswordReset\Persistance\Repository;

use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\Database\Persistence\SchemaBuilder;
use App\Components\PasswordReset\Persistence\Mapper\ActionMapper;
use App\Components\PasswordReset\Persistence\Repository\UserPasswordResetRepository;
use PHPUnit\Framework\TestCase;

class UserPasswordResetRepositoryTest extends TestCase
{

    private UserPasswordResetRepository $repository;

    private SchemaBuilder $schemaBuilder;

    protected function setUp(): void
    {
        $ormSqlConnector = new ORMSqlConnector();

        $this->schemaBuilder = new SchemaBuilder($ormSqlConnector);
        $this->repository = new UserPasswordResetRepository($ormSqlConnector, new ActionMapper());
        $this->schemaBuilder->createSchema();
    }


    public function testGetUserIdFromActionIdWrongId(): void
    {
        $userId = $this->repository->getUserIdFromActionId('3');

        self::assertFalse($userId);
    }

    public function testEmptyActionEntry(): void
    {
        $actionIdEntry = $this->repository->getActionIdEntry('3');

        self::assertFalse($actionIdEntry);

    }

}