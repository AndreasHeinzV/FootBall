<?php

namespace App\Model\Mapper;

use App\Model\DTOs\LeaguesDTO;

interface LeaguesMapperInterface
{
    public function createLeaguesDTO(array $leaguesData);

    public function getLeaguesData(LeaguesDTO $leaguesDTO): array;
}