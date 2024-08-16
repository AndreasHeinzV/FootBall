<?php

namespace App\Model;

interface FootballRepositoryInterface
{
    public function getLeagues(): array;

    public function getCompetition(string $code): array;

    public function getTeam(string $id): array;

    public function getPlayer(string $id): array;
}