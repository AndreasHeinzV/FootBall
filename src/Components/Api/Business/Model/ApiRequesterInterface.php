<?php

namespace App\Components\Api\Business\Model;

use App\Components\Football\DTOs\PlayerDTO;

interface ApiRequesterInterface
{
    public function parRequest($url): array;

    public function getPlayer(string $playerID): ?PlayerDTO;

    public function getTeam(string $id): array;

    public function getCompetition(string $code): array;

    public function getLeagues(): array;
}