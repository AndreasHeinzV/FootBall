<?php

declare(strict_types=1);

namespace App\Components\Football\Mapper;

use App\Components\Football\DTOs\TeamDTO;

class TeamMapper implements TeamMapperInterface
{
    public function createTeamDTO(array $userData): TeamDTO
    {
        return new TeamDTO(

            $userData['playerID'],
            $userData['link'],
            $userData['name']
        );
    }

    public function getTeamData(TeamDTO $teamDTO): array
    {
        return [
            'playerID' => $teamDTO->playerID,
            'link' => $teamDTO->link,
            'name' => $teamDTO->name,
        ];
    }
}