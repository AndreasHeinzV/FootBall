<?php

declare(strict_types=1);

namespace App\Core;

use App\Model\DTOs\UserDTO;
use PDO;
use PDOStatement;

class SqlConnector
{
    private string $dbName = 'football';
    private string $host = '127.0.0.1';
    private int $port = 3336;

    private string $user = 'root';

    private string $pass = 'nexus123';

    private string $dsn;
    private PDO $pdo;

    public function __construct()
    {
        if (isset($_ENV['DATABASE'])) {
            $this->dbName = $_ENV('DATABASE');
        }
        $this->dsn = 'mysql:host=' . $this->host . ':' . $this->port . ';dbname=' . $this->dbName;
        $this->pdo = new PDO($this->dsn, $this->user, $this->pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }


    public function createUserTable(): void
    {
        $statements =
            'CREATE TABLE IF NOT EXISTS users(
    user_id INT AUTO_INCREMENT,
    user_email VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    PRIMARY KEY(user_id)
                  )';
        $stmtFavorites =
            'CREATE TABLE IF NOT EXISTS favorites(
            favorite_id INT AUTO_INCREMENT,
            user_id INT NULL,
            PRIMARY KEY(favorite_id),
            CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(user_id) 
        )';
        $this->pdo->exec($statements);
        $this->pdo->exec($stmtFavorites);
    }

    public function queryInsert(string $query, array $params = []): void
    {
        $stmt = $this->pdo->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
    }

    public function querySelect(string $query, array $params = []): array
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
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
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

}