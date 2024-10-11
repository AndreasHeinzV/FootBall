<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

class Fixtures
{
    public function __construct(public SqlConnector $sqlConnector)
    {
    }

    public function buildTables(): void
    {
        $statements =
            'CREATE TABLE IF NOT EXISTS users(
            user_id INT AUTO_INCREMENT,
            user_email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            first_name VARCHAR(255) NOT NULL,
            last_name VARCHAR(255) NOT NULL,
            PRIMARY KEY(user_id)
        )';

        $stmtFavorites =
            'CREATE TABLE IF NOT EXISTS favorites(
            user_id INT NOT NULL,        
            favorite_position INT AUTO_INCREMENT,
            team_id INT NOT NULL,
            team_name VARCHAR(255) NOT NULL,
            team_crest varchar(255) NOT NULL,
            
            
            
            PRIMARY KEY(favorite_position),
            CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(user_id)
        )';


        $pdo = $this->sqlConnector->getPdo();
        $pdo->exec($statements);
        $pdo->exec($stmtFavorites);
    }

    public function dropTables(): void
    {
        $dropFavorites = 'DROP TABLE IF EXISTS favorites';
        $dropUsers = 'DROP TABLE IF EXISTS users';


        $pdo = $this->sqlConnector->getPdo();
        $pdo->exec($dropFavorites);
        $pdo->exec($dropUsers);

    }
}