<?php

declare(strict_types=1);

namespace App\Components\Football\Business\Model;


use App\Components\Api\Business\ApiRequestFacadeInterface;
use App\Components\Football\DTOs\PlayerDTO;

readonly class FootballBusinessFacade implements FootballBusinessFacadeInterface
{


    public function __construct(private ApiRequestFacadeInterface $apiRequestFacade)
    {
    }

    public function getPlayer(string $id): ?PlayerDTO
    {
        return $this->apiRequestFacade->getPlayer($id);
    }

    public function getTeam(string $id): array
    {
        return $this->apiRequestFacade->getTeam($id);
    }

    public function getCompetition(string $code): array
    {
        return $this->apiRequestFacade->getCompetition($code);
    }

    public function getLeagues(): array
    {
        return $this->apiRequestFacade->getLeagues();
    }
}