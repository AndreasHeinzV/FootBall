<?php

declare(strict_types=1);

namespace App\Model;

class UserEntityManager
{

    public function safeUser(string $filePath, array $existingUsers): void
    {
        file_put_contents($filePath, json_encode($existingUsers, JSON_PRETTY_PRINT));
    }
}