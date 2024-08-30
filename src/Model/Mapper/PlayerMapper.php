<?php

declare(strict_types=1);

namespace App\Model\Mapper;

use App\Model\DTOs\PlayerDTO;

class PlayerMapper
{
    public function createTeamDTO(array $playerData): PlayerDTO
    {
        return new PlayerDTO(
            $playerData['name'],
            $playerData['position'],
            $playerData['dateOfBirth'],
            $playerData['nationality'],
            $playerData['shirtNumber']
        );
    }

    public function getPlayerData(PlayerDTO $playerDTO): array
    {
        return [
            'playerName' => $playerDTO->name,
            'playerPosition' => $playerDTO->position,
            'playerDateOfBirth' => $playerDTO->dateOfBirth,
            'playerNationality' => $playerDTO->nationality,
            'playerShirtNumber' => $playerDTO->shirtNumber,
        ];
    }
}