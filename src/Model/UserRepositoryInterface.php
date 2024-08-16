<?php

namespace App\Model;

interface UserRepositoryInterface
{
    public function getUserName(array $existingUsers,string $email): string;

    public function getUsers(string $filePath): array;
}