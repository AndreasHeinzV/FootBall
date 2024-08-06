<?php


$uri = 'https://api.football-data.org/v4/competitions/';
$reqPrefs['http']['method'] = 'GET';
$reqPrefs['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
$stream_context = stream_context_create($reqPrefs);
$response = file_get_contents($uri, false, $stream_context);
$matches = json_decode($response, true);
//print_r($matches);
if (!isset($_GET['page']) && !isset($_GET['code'])) {

    foreach ($matches['competitions'] as $competition) {
        $id = $competition['id'];
        $name = $competition['code'];

        //     echo $code;
        echo "<a href=/index.php?page=competitions&name=" . $name . ">" . $competition['name'] . "</a><br>";


        //  echo $_GET['name']. "<br>";
    }

}
//testOutput();
competitionSorted();

//kader();
echo '<br>';


function testOutput()
{

    $code = $_GET['name'];
    $pageSite = $_GET['page'];

    if (!is_null(isset($code))) {

        $uri = 'https://api.football-data.org/v4/competitions/' . $code . '/teams';
        $reqPrefs['http']['method'] = 'GET';
        $reqPrefs['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
        $stream_context = stream_context_create($reqPrefs);
        $response = file_get_contents($uri, false, $stream_context);
        $teams = json_decode($response, true);

        $uri = 'https://api.football-data.org/v4/competitions/' . $code . '/standings';
        $reqPref['http']['method'] = 'GET';
        $reqPref['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
        $stream_context = stream_context_create($reqPref);
        $response = file_get_contents($uri, false, $stream_context);
        $standings = json_decode($response, true);


        // print_r($standings);
        // $idKader = $_GET['id'];
        //echo $pageSite;
        if (!is_null(!isset($_GET['id'])) && $pageSite === "competitions") {

            echo "<table>";

            echo "<tr> <th>Position</th> <th>Team</th> <th>Played</th><th>Won</th><th>Draw</th><th>Lost</th><th>Points</th><th>GoalsFor</th><th>GoalsAgainst</th><th>GoalsDifference</th></tr>";
            foreach ($teams['teams'] as $team) {
                $id = $team['id'];
                $teamID = $standings['standings'][0]['table'];
                //var_dump($teamID);


                foreach ($teamID as $table) {

                    if ($table['team']['id'] === $id) {
                        echo "<tr>";
                        echo
                            "<td>" . $table['position'] . "</td>";

                        echo "<td> <a href=/index.php?page=team&id=" . $table['team']['id'] . ">" . $table['team']['name'] . "</a></td>";

                        echo
                            "<td>" . $table['playedGames'] . "</td>" .

                            "<td>" . $table['won'] . "</td>" .

                            "<td>" . $table['draw'] . "</td>" .

                            "<td>" . $table['lost'] . "</td>" .

                            "<td>" . $table['points'] . "</td>" .

                            "<td>" . $table['goalsFor'] . "</td>" .

                            "<td>" . $table['goalsAgainst'] . "</td>" .

                            "<td>" . $table['goalDifference'] . "</td>";


                        echo "</tr>";
                    }

                }

            }
            echo "</table><br>";
        }

        //  echo "Position: " . $position . "Team: " . $team['name'] . " ID: " . $id . " games: " . $games . " GoalDiff: " . $goals . " Wins: " . $wins . "Loses: " . $loses . "<br>";
    }
    kader();

}

function competitionSorted()
{

    $code = $_GET['name'];
    $pageSite = $_GET['page'];

    if (!is_null(isset($code))) {

        $uri = 'https://api.football-data.org/v4/competitions/' . $code . '/teams';
        $reqPrefs['http']['method'] = 'GET';
        $reqPrefs['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
        $stream_context = stream_context_create($reqPrefs);
        $response = file_get_contents($uri, false, $stream_context);
        $teams = json_decode($response, true);

        $uri = 'https://api.football-data.org/v4/competitions/' . $code . '/standings';
        $reqPref['http']['method'] = 'GET';
        $reqPref['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
        $stream_context = stream_context_create($reqPref);
        $response = file_get_contents($uri, false, $stream_context);
        $standings = json_decode($response, true);


        if (!is_null(!isset($_GET['id'])) && $pageSite === "competitions") {
            //  echo $_GET['id']. "hallo";
            echo "<table>";


            //   foreach ($teams['teams'] as $team) {
            $id = $teams['id'];
            $teamID = $standings['standings'][0]['table'];

          //  print_r($standings);
            echo "<tr> <th>Position</th> <th>Team</th> <th>Played</th><th>Won</th><th>Draw</th><th>Lost</th><th>Points</th><th>GoalsFor</th><th>GoalsAgainst</th><th>GoalsDifference</th></tr>";
            foreach ($teamID as $table) {
                //  print_r($table);
                //  if ($table['team']['id'] === $id) {

                echo "<tr>";
                echo
                    "<td>" . $table['position'] . "</td>";

                echo "<td> <a href=/index.php?page=team&id=" . $table['team']['id'] . ">" . $table['team']['name'] . "</a></td>";
                echo
                    "<td>" . $table['playedGames'] . "</td>" .

                    "<td>" . $table['won'] . "</td>" .

                    "<td>" . $table['draw'] . "</td>" .

                    "<td>" . $table['lost'] . "</td>" .

                    "<td>" . $table['points'] . "</td>" .

                    "<td>" . $table['goalsFor'] . "</td>" .

                    "<td>" . $table['goalsAgainst'] . "</td>" .

                    "<td>" . $table['goalDifference'] . "</td>";

                echo "</tr>";
                // }

            }

        }
        echo "</table><br>";
        //}

        //  echo "Position: " . $position . "Team: " . $team['name'] . " ID: " . $id . " games: " . $games . " GoalDiff: " . $goals . " Wins: " . $wins . "Loses: " . $loses . "<br>";
    }
    kader();

}

function kader()
{
    $id = $_GET['id'];
    $page = $_GET['page'];

    $uri = 'https://api.football-data.org/v4/teams/' . $id;
    $reqPrefs['http']['method'] = 'GET';
    $reqPrefs['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
    $stream_context = stream_context_create($reqPrefs);
    $response = file_get_contents($uri, false, $stream_context);
    $team = json_decode($response, true);

    //echo $_GET['id'];
    if (!is_null(isset($id)) && $page === "team") {
        echo "<h1>Kader</h1>" . "<br>";
        echo "<ul>";
        foreach ($team['squad'] as $player) {
            $playerID = $player['id'];
            //echo "<li>" . $player['name'] . " Player ID: ". $playerID. "</li>";
            echo "<li><a href=/index.php?page=player&id=" . $playerID . ">" . $player['name'] . "</a></li>";
        }
        echo "</ul>";


    }
    playerOutput();
}

function playerOutput()
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

        $playerName = $player['name'];
        $playerPosition = $player['position'];
        $playerDate = $player['dateOfBirth'];
        $playerNationality = $player['nationality'];
        $playerShirtNumber = $player['shirtNumber'];

        echo "<h1>" . $playerName . "</h1>" . "<br>" .
            "<ul>" .
            "<li> Positon: " . $playerPosition . "</li>" .
            "<li>Geburtsdatum: " . $playerDate . "</li>" .
            "<li>Nationalität: " . $playerNationality . "</li>" .
            "<li>Trikotnummer: " . $playerShirtNumber . "</li>" .
            "</ul>";

    }

}

?>


<?php function test()
{
    $uri = 'https://api.football-data.org/v2/competitions/PL/standings';
    $reqPrefs['http']['method'] = 'GET';
    $reqPrefs['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
    $stream_context = stream_context_create($reqPrefs);
    $response = file_get_contents($uri, false, $stream_context);
    $matches = json_decode($response, true);
    var_dump($matches);
}

?>




