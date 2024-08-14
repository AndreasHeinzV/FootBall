<?php

declare(strict_types=1);

namespace App\Model;

class FootballRepository
{
    private ApiRequester $apiRequester;

    function __construct()
    {
        $this->apiRequester = new ApiRequester();
    }

    public function getPlayer(string $id): array
    {
        $uri = 'https://api.football-data.org/v4/persons/' . $id;
        $player = $this->apiRequester->parRequest($uri);


        return [
            "playerName" => $player['name'],
            "playerPosition" => $player['position'],
            "playerDate" => $player['dateOfBirth'],
            "playerNationality" => $player['nationality'],
            "playerShirtNumber" => $player['shirtNumber'],
        ];
    }

    public function getTeam(string $id): array
    {
        $uri = 'https://api.football-data.org/v4/teams/' . $id;
        $team = $this->apiRequester->parRequest($uri);
        $playersArray = [];

        foreach ($team['squad'] as $player) {
            $playerArray = [];
            $playerID = $player['id'];
            $playerArray['playerID'] = $playerID;
            $playerArray['link'] = "/index.php?page=player&id=" . $playerID;
            $playerArray['name'] = $player['name'];
            $playersArray[] = $playerArray;
        }
        return $playersArray;
    }

    public function getCompetition(string $code): array
    {
        $teamsArray = [];
        $uri = 'https://api.football-data.org/v4/competitions/' . $code . '/standings';
        $standings = $this->apiRequester->parRequest($uri);


        $teamID = $standings['standings'][0]['table'];
        foreach ($teamID as $table) {
            $teamArray = [];
            $teamArray['position'] = $table['position'];
            $teamArray['name'] = $table['team']['name'];
            $teamArray['link'] = "/index.php?page=team&id=" . $table['team']['id'];
            $teamArray['playedGames'] = $table['playedGames'];
            $teamArray['won'] = $table['won'];
            $teamArray['draw'] = $table['draw'];
            $teamArray['lost'] = $table['lost'];
            $teamArray['points'] = $table['points'];
            $teamArray['goalsFor'] = $table['goalsFor'];
            $teamArray['goalsAgainst'] = $table['goalsAgainst'];
            $teamArray['goalDifference'] = $table['goalDifference'];
            $teamsArray[] = $teamArray;
        }
        return $teamsArray;
    }

    public function getLeagues(): array
    {
        $uri = 'https://api.football-data.org/v4/competitions/';
        $matches = $this->apiRequester->parRequest($uri);
        $leaguesArray = [];


        foreach ($matches['competitions'] as $competition) {
            $leagueArray = [];
            $leagueArray['id'] = $competition['id'];
            $leagueArray['link'] = "/index.php?page=competitions&name=" . $competition['code'];
            $leagueArray['name'] = $competition['name'];

            $leaguesArray[] = $leagueArray;
        }
        return $leaguesArray;
    }


}