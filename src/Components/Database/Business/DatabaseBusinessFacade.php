<?php

declare(strict_types=1);

namespace App\Components\Database\Business;

use App\Components\Database\Business\Model\FixturesInterface;

readonly class DatabaseBusinessFacade implements DatabaseBusinessFacadeInterface
{
    public function __construct(
        private FixturesInterface $fixtures
    ) {
    }

    public function createUserTables(): void
    {
        $this->fixtures->buildTables();
    }

    public function dropUserTables(): void
    {
        $this->fixtures->dropTables();
    }
}