<?php

namespace App\Model;

class UserEntityManager
{

    public function safeUser($filePath, $existingUsers): void
    {
        file_put_contents($filePath,json_encode($existingUsers, JSON_PRETTY_PRINT));
    }
}