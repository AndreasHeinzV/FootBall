<?php

namespace App\Components\Api\Business;

use App\Components\Football\DTOs\PlayerDTO;

interface ApiRequestFacadeInterface
{
    public function getPlayer(string $id): ?PlayerDTO;

    public function getTeam(string $id): array;

    public function getCompetition(string $code): array;

    public function getLeagues(): array;
}