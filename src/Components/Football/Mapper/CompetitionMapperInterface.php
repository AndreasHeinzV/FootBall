<?php

namespace App\Components\Football\Mapper;

use App\Components\Football\DTOs\CompetitionDTO;

interface CompetitionMapperInterface
{
    public function createCompetitionDTO(array $competitionData): CompetitionDTO;

    public function getCompetitionData(CompetitionDTO $competitionDTO): array;
}