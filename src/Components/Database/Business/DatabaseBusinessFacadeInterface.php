<?php

namespace App\Components\Database\Business;

interface DatabaseBusinessFacadeInterface
{
    public function createUserTables(): void;

    public function dropUserTables(): void;
}