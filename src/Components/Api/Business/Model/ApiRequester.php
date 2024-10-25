<?php

namespace App\Components\Api\Business\Model;

use App\Components\Football\DTOs\PlayerDTO;
use App\Components\Football\Mapper\CompetitionMapperInterface;
use App\Components\Football\Mapper\LeaguesMapperInterface;
use App\Components\Football\Mapper\PlayerMapperInterface;
use App\Components\Football\Mapper\TeamMapperInterface;

class ApiRequester implements ApiRequesterInterface
{
    private $apiKey;


    public function __construct(
        private readonly LeaguesMapperInterface $leaguesMapper,
        private readonly CompetitionMapperInterface $competitionMapper,
        private readonly TeamMapperInterface $teamMapper,
        private readonly PlayerMapperInterface $playerMapper
    ) {
        $this->apiKey = (new ApiKey)->getApiKey();
    }

    public function parRequest($url): array
    {
        /*
         // need this to get testData
         //  $filename = str_replace(['https://api.football-data.org/v4/', '/'], [''], $url);
         //  file_put_contents(__DIR__ . '/' . $filename . '.json', $response);

        try {
        */
            $curl = curl_init($url);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['X-Auth-Token: ' . $this->apiKey]);

            $response = curl_exec($curl);

            if ($response === false) {
            //    $error = curl_error($curl);
                curl_close($curl);
                return [];
            }
            $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($httpStatus !== 200) {
                return [];
            }
            return json_decode($response, true);
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