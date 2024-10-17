<?php

namespace App\Components\Football\Mapper;

use App\Components\Football\DTOs\LeaguesDTO;

interface LeaguesMapperInterface
{
    public function createLeaguesDTO(array $leaguesData);

    public function getLeaguesData(LeaguesDTO $leaguesDTO): array;
}