<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\DTOs\PlayerDTO;

class FootballRepository implements FootballRepositoryInterface
{
    private ApiRequesterInterface $apiRequester;

    public function __construct(ApiRequesterInterface $apiRequester)
    {
        $this->apiRequester = $apiRequester;
    }

    public function getPlayer(string $id): ?PlayerDTO
    {
        return $this->apiRequester->getPlayer($id);
    }

    public function getTeam(string $id): array
    {
        return $this->apiRequester->getTeam($id);
    }

    public function getCompetition(string $code): array
    {
        return $this->apiRequester->getCompetition($code);
    }

    public function getLeagues(): array
    {
        return $this->apiRequester->getLeagues();
    }
}