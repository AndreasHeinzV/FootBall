<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\ApiRequest;

use App\Components\Api\Business\Model\ApiRequesterInterface;
use App\Components\Football\DTOs\PlayerDTO;
use App\Components\Football\Mapper\CompetitionMapper;
use App\Components\Football\Mapper\LeaguesMapperInterface;
use App\Components\Football\Mapper\PlayerMapper;
use App\Components\Football\Mapper\TeamMapper;

readonly class ApiRequesterFaker implements ApiRequesterInterface
{


    public function __construct(
        private LeaguesMapperInterface $leaguesMapper,
        private CompetitionMapper $competitionMapper,
        private TeamMapper $teamMapper,
        private PlayerMapper $playerMapper
    ) {
    }

    public function parRequest($url): array
    {
        $filename = str_replace(['https://api.football-data.org/v4/', '/'], [''], $url);
        $path = __DIR__ . '/cache/' . $filename . '.json';

        if (!file_exists($path)) {
            return [];
        }

        return json_decode(file_get_contents($path), true);
    }

    public function getPlayer(string $playerID): ?PlayerDTO
    {
        $uri = 'https://api.football-data.org/v4/persons/' . $playerID;
        if (empty($this->parRequest($uri))) {
            return null;
        }
        return $this->playerMapper->createTeamDTO($this->parRequest($uri));
    }

    public function getTeam(string $id): array
    {
        $uri = 'https://api.football-data.org/v4/teams/' . $id;
        $team = $this->parRequest($uri);
        $playersArray = [];

        if (empty($team)) {
            return [];
        }

        $playersArray['teamName'] = $team['name'];
        $playersArray['teamID'] = $team['id'];
        $playersArray['crest'] = $team['crest'];

        foreach ($team['squad'] as $player) {
            $playerArray['playerID'] = $player['id'];
            $playerArray['link'] = "/index.php?page=player&id=" . $player['id'];
            $playerArray['name'] = $player['name'];
            $playersArray['squad'][] = $this->teamMapper->createTeamDTO($playerArray);
        }
        return $playersArray;
    }

    public function getCompetition(string $code): array
    {
        $teams = [];
        $uri = 'https://api.football-data.org/v4/competitions/' . $code . '/standings';
        $standings = $this->parRequest($uri);

        if (empty($standings)) {
            return [];
        }

        $teamID = $standings['standings'][0]['table'];
        foreach ($teamID as $table) {
            $team = [];
            $team['position'] = $table['position'];
            $team['name'] = $table['team']['name'];
            $team['link'] = "/index.php?page=team&id=" . $table['team']['id'];
            $team['playedGames'] = $table['playedGames'];
            $team['won'] = $table['won'];
            $team['draw'] = $table['draw'];
            $team['lost'] = $table['lost'];
            $team['points'] = $table['points'];
            $team['goalsFor'] = $table['goalsFor'];
            $team['goalsAgainst'] = $table['goalsAgainst'];
            $team['goalDifference'] = $table['goalDifference'];;
            $teams[] = $this->competitionMapper->createCompetitionDTO($team);
        }

        return $teams;
    }

    public function getLeagues(): array
    {
        $uri = 'https://api.football-data.org/v4/competitions/';
        $matches = $this->parRequest($uri);
        $leaguesArray = [];
        if (empty($matches)) {
            return [];
        }

        foreach ($matches['competitions'] as $competition) {
            $leagueArray = [];
            $leagueArray['id'] = $competition['id'];
            $leagueArray['link'] = "/index.php?page=competitions&name=" . $competition['code'];
            $leagueArray['name'] = $competition['name'];
            $leagueDTO = $this->leaguesMapper->createLeaguesDTO($leagueArray);

            $leaguesArray[] = $leagueDTO;
        }
        return $leaguesArray;
    }
}