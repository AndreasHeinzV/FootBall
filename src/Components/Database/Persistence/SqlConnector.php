<?php

declare(strict_types=1);

namespace App\Components\Database\Persistence;

use PDO;

class SqlConnector implements SqlConnectorInterface
{
    private string $dbName = 'football';
    private string $host = '127.0.0.1';
    private int $port = 3336;

    private string $user = 'root';

    private string $pass = 'nexus123';

    private string $dsn;
    private PDO $pdo;
/*
    public function __construct()
    {
        if (isset($_ENV['DATABASE'])) {
            $this->dbName = $_ENV['DATABASE'];
        }
        $this->dsn = 'mysql:host=' . $this->host . ':' . $this->port . ';dbname=' . $this->dbName;
        $this->pdo = new PDO($this->dsn, $this->user, $this->pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }


    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function queryInsert(string $query, array $params = []): void
    {
        $stmt = $this->pdo->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
    }

    public function querySelect(string $query, array $params = []): array|false
    {
        $stmt = $this->pdo->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function querySelectAll(string $query, array $params = []): array
    {
        $stmt = $this->pdo->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryManipulate(string $query, array $params = []): void
    {
        $stmt = $this->pdo->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();
    }
*/
}