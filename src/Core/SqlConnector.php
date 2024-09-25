<?php

declare(strict_types=1);

namespace App\Core;

use App\Model\DTOs\UserDTO;
use PDO;
use PDOStatement;

class SqlConnector
{
    private $dbName = 'football';
    private $host = '127.0.0.1';
    private $port = 3336;

    private $user = 'root';

    private $pass = 'nexus123';

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
            'CREATE TABLE users(
    user_id INT AUTO_INCREMENT,
    user_email VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    PRIMARY KEY(user_id)
                  )';

        $this->pdo->exec($statements);
    }

    public function query(string $query, array $params = []): array
    {
        $this->pdo->prepare($query);
        return [];
    }
}