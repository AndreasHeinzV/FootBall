<?php

namespace App\Components\Football\Mapper;

use App\Components\Football\DTOs\PlayerDTO;

interface PlayerMapperInterface
{
    public function createTeamDTO(array $playerData): PlayerDTO;
    public function getPlayerData(PlayerDTO $playerDTO): array;
}