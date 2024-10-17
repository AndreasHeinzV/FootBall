<?php

namespace App\Components\Football\Mapper;

use App\Components\Football\DTOs\TeamDTO;

interface TeamMapperInterface
{
    public function createTeamDTO(array $userData): TeamDTO;

    public function getTeamData(TeamDTO $teamDTO): array;
}