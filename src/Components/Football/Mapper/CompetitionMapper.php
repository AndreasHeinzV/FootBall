<?php

declare(strict_types=1);

namespace App\Components\Football\Mapper;


use App\Components\Football\DTOs\CompetitionDTO;

class CompetitionMapper implements CompetitionMapperInterface
{
    public function createCompetitionDTO(array $competitionData): CompetitionDTO
    {
        return new CompetitionDTO(
            $competitionData['position'],
            $competitionData['name'],
            $competitionData['link'],
            $competitionData['playedGames'],
            $competitionData['won'],
            $competitionData['draw'],
            $competitionData['lost'],
            $competitionData['points'],
            $competitionData['goalsFor'],
            $competitionData['goalsAgainst'],
            $competitionData['goalDifference']
        );
    }

    public function getCompetitionData(CompetitionDTO $competitionDTO): array
    {
        return [
            'position' => $competitionDTO->position,
            'name' => $competitionDTO->name,
            'link' => $competitionDTO->link,
            'playedGames' => $competitionDTO->playedGames,
            'won' => $competitionDTO->won,
            'draw' => $competitionDTO->draw,
            'lost' => $competitionDTO->lost,
            'points' => $competitionDTO->points,
            'goalsFor' => $competitionDTO->goalsFor,
            'goalsAgainst' => $competitionDTO->goalsAgainst,
            'goalDifference' => $competitionDTO->goalDifference,
        ];
    }
}