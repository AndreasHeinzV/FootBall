<?php
session_start();


if (isset($_SESSION['loginStatus']) && $_SESSION['loginStatus'] === true){
    $sessionUsername = $_SESSION['userName'];
    $loginStatus = $_SESSION['loginStatus'];



}
function indexRun(): array
{
    $uri = 'https://api.football-data.org/v4/competitions/';
    $reqPref['http']['method'] = 'GET';
    $reqPref['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
    $stream_context = stream_context_create($reqPref);
    $response = file_get_contents($uri, false, $stream_context);
    $matches = json_decode($response, true);
    $leaguesArray = [];


    if (!isset($_GET['page']) && !isset($_GET['code'])) {

        foreach ($matches['competitions'] as $competition) {


            $leagueArray = [];
            $leagueArray['id'] = $competition['id'];
            $leagueArray['link'] = "/index.php?page=competitions&name=" . $competition['code'];
            $leagueArray['name'] = $competition['name'];

            $leaguesArray[] = $leagueArray;
        }
        return $leaguesArray;
    }
    return [];
}

require_once __DIR__ . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader);


$page = $_GET['page'] ?? '';

switch ($page) {

    case 'player':
        $playerArray = playerOutput();
        $playerName = array_shift($playerArray);


        echo $twig->render('player.twig', [
                'playerName' => $playerName,
                'playerData' => $playerArray,]
        );
        break;

    case 'competitions':
        $teamsArray = competitionSorted();
        echo $twig->render('competitions.twig', [

                'teams' => $teamsArray]
        );

        break;

    case 'team':

        $kadersArray = kader();
        echo $twig->render('kader.twig', [

                'players' => $kadersArray]
        );
        break;

    default:
        $leaguesArray = indexRun();



        echo $twig->render('index.twig', [

                'leagues' => $leaguesArray,
                'userName' => $sessionUsername,
                'status' => $loginStatus]

        );


        break;
}


function competitionSorted(): array
{

    $code = $_GET['name'];
    $pageSite = $_GET['page'];
    $teamsArray = [];
    if (isset($_GET['name']) && isset($_GET['page']) && $_GET['page'] === "competitions") {


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
    //in case if doesnt work
    return [];

}

function kader(): array
{
    $id = $_GET['id'];
    $page = $_GET['page'];


    $uri = 'https://api.football-data.org/v4/teams/' . $id;
    $reqPrefs['http']['method'] = 'GET';
    $reqPrefs['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
    $stream_context = stream_context_create($reqPrefs);
    $response = file_get_contents($uri, false, $stream_context);
    $team = json_decode($response, true);

    $playersArray = [];
    if (isset($id) && $page === "team") {


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
    return [];
}

function playerOutput(): array
{


    $id = $_GET['id'];
    $page = $_GET['page'];

    if (!is_null(isset($id)) && $page === "player") {
        $uri = 'https://api.football-data.org/v4/persons/' . $id;
        $reqPrefs['http']['method'] = 'GET';
        $reqPrefs['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
        $stream_context = stream_context_create($reqPrefs);
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
    return [];
}









