<?php

namespace App\Components\Football\Business\Model;

use App\Components\Football\DTOs\PlayerDTO;

interface FootballBusinessFacadeInterface
{
    public function getLeagues(): array;

    public function getCompetition(string $code): array;

    public function getTeam(string $id): array;

    public function getPlayer(string $id): ?PlayerDTO;
}