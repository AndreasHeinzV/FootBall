<?php

declare(strict_types=1);

namespace App\Components\Api\Business;

use App\Components\Api\Business\Model\ApiRequesterInterface;
use App\Components\Football\DTOs\PlayerDTO;

readonly class ApiRequestFacade implements ApiRequestFacadeInterface
{

    public function __construct(private ApiRequesterInterface $apiRequester)
    {
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