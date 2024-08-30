<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\ApiRequesterInterface;
use App\Model\DTOs\PlayerDTO;
use App\Model\Mapper\CompetitionMapper;
use App\Model\Mapper\LeaguesMapperInterface;
use App\Model\Mapper\PlayerMapper;
use App\Model\Mapper\TeamMapper;
use App\Model\Mapper\UserMapperInterface;


class FootballRepository implements FootballRepositoryInterface
{
    private ApiRequesterInterface $apiRequester;

    private LeaguesMapperInterface $leaguesMapper;

    private CompetitionMapper $competitionMapper;

    private TeamMapper $teamMapper;

    private PlayerMapper $playerMapper;

    public function __construct(
        ApiRequesterInterface $apiRequester,
        LeaguesMapperInterface $leaguesMapper,
        CompetitionMapper $competitionMapper,
        TeamMapper $teamMapper,
        PlayerMapper $playerMapper
    ) {
        $this->leaguesMapper = $leaguesMapper;
        $this->apiRequester = $apiRequester;
        $this->competitionMapper = $competitionMapper;
        $this->teamMapper = $teamMapper;
        $this->playerMapper = $playerMapper;
    }

    public function getPlayer(string $id): PlayerDTO
    {
        $uri = 'https://api.football-data.org/v4/persons/' . $id;
        $player = $this->apiRequester->parRequest($uri);
        return $this->playerMapper->createTeamDTO($player);

    }

    public function getTeam(string $id): array
    {
        $uri = 'https://api.football-data.org/v4/teams/' . $id;
        $team = $this->apiRequester->parRequest($uri);
        $playersArray = [];

        foreach ($team['squad'] as $player) {
            //$playerArray = [];
            $playerArray['playerID'] = $player['id'];
            $playerArray['link'] = "/index.php?page=player&id=" . $player['id'];
            $playerArray['name'] = $player['name'];
            $playersArray[] = $this->teamMapper->createTeamDTO($playerArray);
        }
       // var_export($playerArray);
        return $playersArray;
    }

    public function getCompetition(string $code): array
    {
        $teams = [];
        $uri = 'https://api.football-data.org/v4/competitions/' . $code . '/standings';
        $standings = $this->apiRequester->parRequest($uri);
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
        $matches = $this->apiRequester->parRequest($uri);
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