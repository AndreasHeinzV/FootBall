<?php

namespace App\Core;

interface ValidationInterface
{

    public function checkDuplicateMail(array $existingUsers, string $mailToCheck): bool;

    public function checkForErrors(array $data): array;
}