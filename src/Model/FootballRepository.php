<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\ApiRequesterInterface;

class FootballRepository implements FootballRepositoryInterface, RepositoryInterface
{
    private  ApiRequesterInterface $apiRequester;
    private array $playerData;

    public function __construct(ApiRequesterInterface $apiRequester)
    {
        $this->apiRequester = $apiRequester;

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
            $team['goalDifference'] = $table['goalDifference'];

            $teams[] = $team;
        }
        return $teams;
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


    public function load(): void
    {
        // TODO: Implement load() method.
    }
}