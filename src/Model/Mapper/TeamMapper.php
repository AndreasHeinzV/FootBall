<?php

declare(strict_types=1);

namespace App\Model\Mapper;

use App\Model\DTOs\TeamDTO;

class TeamMapper
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