<?php

declare(strict_types=1);

namespace App\Model\Mapper;

use App\Model\DTOs\TeamDTO;

class TeamMapper
{
    public function createTeamDTO(array $userData): TeamDTO
    {
        return new TeamDTO(
            /*
            $userData['teamName'],
            $userData['teamID'],
            $userData['crest'],
            */
            $userData['playerID'],
            $userData['link'],
            $userData['name']
        );
    }

    public function getTeamData(TeamDTO $teamDTO): array
    {
        return [
            /*
            'teamName' => $teamDTO->teamName,
            'teamID' => $teamDTO->teamID,
            'crest' => $teamDTO->crest,
            */
            'playerID' => $teamDTO->playerID,
            'link' => $teamDTO->link,
            'name' => $teamDTO->name,
        ];
    }
}