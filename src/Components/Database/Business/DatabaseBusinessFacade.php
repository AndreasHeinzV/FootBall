<?php

declare(strict_types=1);

namespace App\Components\Database\Business;

use App\Components\Database\Business\Model\FixturesInterface;
use App\Components\Database\Persistence\SchemaBuilder;

readonly class DatabaseBusinessFacade implements DatabaseBusinessFacadeInterface
{
    public function __construct(
        private SchemaBuilder $schemaBuilder,
    ) {
    }

    public function createUserTables(): void
    {
        $this->schemaBuilder->createSchema();
    }

    public function dropUserTables(): void
    {
        $this->schemaBuilder->dropSchema();
    }
}