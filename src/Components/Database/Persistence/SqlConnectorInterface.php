<?php

namespace App\Components\Database\Persistence;

use PDO;

interface SqlConnectorInterface
{
    public function getPdo(): PDO;

    public function queryInsert(string $query, array $params = []): void;

    public function querySelect(string $query, array $params = []): array|false;

    public function querySelectAll(string $query, array $params = []): array;

    public function queryManipulate(string $query, array $params = []): void;
}