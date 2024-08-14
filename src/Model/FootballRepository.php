<?php

namespace App\Model;

class FootballRepository
{
    public function getPlayer($id): array
    {
        $uri = 'https://api.football-data.org/v4/persons/' . $id;
        $reqPref['http']['method'] = 'GET';
        $reqPref['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
        $stream_context = stream_context_create($reqPref);
        $response = file_get_contents($uri, false, $stream_context);
        $player = json_decode($response, true);


        return [
            "playerName" => $player['name'],
            "playerPosition" => $player['position'],
            "playerDate" => $player['dateOfBirth'],
            "playerNationality" => $player['nationality'],
            "playerShirtNumber" => $player['shirtNumber'],
        ];
    }

    public function getSquad($id): array
    {
        $uri = 'https://api.football-data.org/v4/teams/' . $id;
        $reqPref['http']['method'] = 'GET';
        $reqPref['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
        $stream_context = stream_context_create($reqPref);
        $response = file_get_contents($uri, false, $stream_context);
        $team = json_decode($response, true);
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

    public function getCompetition($code): array
    {
        $teamsArray = [];


        $uri = 'https://api.football-data.org/v4/competitions/' . $code . '/standings';
        $reqPref['http']['method'] = 'GET';
        $reqPref['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
        $stream_context = stream_context_create($reqPref);
        $response = file_get_contents($uri, false, $stream_context);
        $standings = json_decode($response, true);


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
        $reqPref['http']['method'] = 'GET';
        $reqPref['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
        $stream_context = stream_context_create($reqPref);
        $response = file_get_contents($uri, false, $stream_context);
        $matches = json_decode($response, true);
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