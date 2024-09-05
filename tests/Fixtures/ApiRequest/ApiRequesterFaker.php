<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\ApiRequest;

use App\Model\ApiRequesterInterface;
use App\Model\DTOs\PlayerDTO;
use App\Model\Mapper\CompetitionMapper;
use App\Model\Mapper\LeaguesMapperInterface;
use App\Model\Mapper\PlayerMapper;
use App\Model\Mapper\TeamMapper;

class ApiRequesterFaker implements ApiRequesterInterface
{
    private LeaguesMapperInterface $leaguesMapper;

    private CompetitionMapper $competitionMapper;

    private TeamMapper $teamMapper;

    private PlayerMapper $playerMapper;

    public function __construct(
        LeaguesMapperInterface $leaguesMapper,
        CompetitionMapper $competitionMapper,
        TeamMapper $teamMapper,
        PlayerMapper $playerMapper
    ) {
        $this->leaguesMapper = $leaguesMapper;
        $this->competitionMapper = $competitionMapper;
        $this->teamMapper = $teamMapper;
        $this->playerMapper = $playerMapper;
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

    public function getPlayer(string $playerID): PlayerDTO
    {
        $uri = 'https://api.football-data.org/v4/persons/' . $playerID;
        $player = $this->parRequest($uri);
        return $this->playerMapper->createTeamDTO($player);
    }

    public function getTeam(string $id): array
    {
        $uri = 'https://api.football-data.org/v4/teams/' . $id;
        $team = $this->parRequest($uri);
        $playersArray = [];

        foreach ($team['squad'] as $player) {
            //$playerArray = [];
            $playerArray['playerID'] = $player['id'];
            $playerArray['link'] = "/index.php?page=player&id=" . $player['id'];
            $playerArray['name'] = $player['name'];
            $playersArray[] = $this->teamMapper->createTeamDTO($playerArray);
        }
        return $playersArray;
    }

    public function getCompetition(string $code): array
    {
        $teams = [];
        $uri = 'https://api.football-data.org/v4/competitions/' . $code . '/standings';
        $standings = $this->parRequest($uri);
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
        // var_dump($teams);
        return $teams;
    }

    public function getLeagues(): array
    {
        $uri = 'https://api.football-data.org/v4/competitions/';
        $matches = $this->parRequest($uri);
        $leaguesArray = [];

        //var_export($matches['competitions']);
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