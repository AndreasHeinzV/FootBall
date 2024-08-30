<?php

declare(strict_types=1);

namespace App\Model\Mapper;

use App\Model\DTOs\LeaguesDTO;

class LeaguesMapper implements LeaguesMapperInterface
{

    public function createLeaguesDTO(array $leaguesData): LeaguesDTO
    {
        return new leaguesDTO(
            $leaguesData['id'],
            $leaguesData['name'],
            $leaguesData['link']
        );
    }

    public function getLeaguesData(LeaguesDTO $leaguesDTO): array
    {
        return [
            'id' => $leaguesDTO->id,
            'name' => $leaguesDTO->name,
            'link' => $leaguesDTO->link,
        ];
    }
}