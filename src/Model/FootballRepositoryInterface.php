<?php

namespace App\Model;

use App\Model\DTOs\PlayerDTO;

interface FootballRepositoryInterface
{
    public function getLeagues(): array;

    public function getCompetition(string $code): array;

    public function getTeam(string $id): array;

    public function getPlayer(string $id): ?PlayerDTO;
}