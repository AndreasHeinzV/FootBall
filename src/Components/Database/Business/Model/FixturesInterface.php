<?php

namespace App\Components\Database\Business\Model;

interface FixturesInterface
{
    public function buildTables(): void;
    public function dropTables(): void;
}