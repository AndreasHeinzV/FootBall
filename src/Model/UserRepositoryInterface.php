<?php

namespace App\Model;

interface UserRepositoryInterface
{
    public function getUserName(array $existingUsers,string $email): string;

    public function getUsers(): array;
    public function getFilePath(): string;
}