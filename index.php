<?php


$uri = 'https://api.football-data.org/v4/competitions/';
$reqPrefs['http']['method'] = 'GET';
$reqPrefs['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
$stream_context = stream_context_create($reqPrefs);
$response = file_get_contents($uri, false, $stream_context);
$matches = json_decode($response, true);

if (!isset($_GET['page']) && !isset($_GET['code'])) {
    foreach ($matches['competitions'] as $competition) {
        // var_dump($competition['id'], $competition['name']);
        ///index.php?page=competition&id=$competition['id'],
        //<a href=index?$competition=><$competition['name']</a>;
        $id = $competition['id'];
        $name = $competition['code'];

        //     echo $code;
        echo "<a href=/index.php?page=competitions&name=" . $name . ">" . $competition['name'] . "</a><br>";


        //  echo $_GET['name']. "<br>";
    }

}
testOutput();
echo '<br>';
//testOutputStanding();
//request();


/*function request()
{

    $uri = 'https://api.football-data.org/v2/competitions/';
    $reqPrefs['http']['method'] = 'GET';
    $reqPrefs['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
    $stream_context = stream_context_create($reqPrefs);
    $response = file_get_contents($uri, false, $stream_context);
    $matches = json_decode($response, true);

    if (!isset($_GET['page']) && !isset($_GET['name'])) {
        foreach ($matches['competitions'] as $competition) {
            // var_dump($competition['id'], $competition['name']);
            ///index.php?page=competition&id=$competition['id'],
            //<a href=index?$competition=><$competition['name']</a>;
            $id = $competition['id'];
            $name = $competition['name'];
            $code = $competition['code'];
            echo $code;
       //     echo $code;
            echo "<a href=/index.php?page=" . $id . "&name=" . $name . ">" . $competition['name'] . "</a><br>";


            //  echo $_GET['name']. "<br>";
        }

    }
    testOutput($code);

}*/

function testOutput()
{

    $code = $_GET['name'];


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
        foreach ($teams['teams'] as $team) {
           $id = $team['id'];
            $teamID = $standings['standings'][0]['table'];
            //var_dump($teamID);

            foreach ($teamID as $table) {
               // var_dump($teamID);
                if ($table['team']['id'] === $id) {
                    echo $table['team']['name'] . $table['team']. "<br>";

                }



            }
            if ($standings[$id] === $id) {
                $position = $standings[$id]['position'];
                $goals = $standings[$id]['goals'];
                $games = $standings[$id]['playedGames'];
                $wins = $standings[$id]['wins'];
                $loses = $standings[$id]['losses'];
            }
          //  echo "Position: " . $position . "Team: " . $team['name'] . " ID: " . $id . " games: " . $games . " GoalDiff: " . $goals . " Wins: " . $wins . "Loses: " . $loses . "<br>";
        }


    }


}

function getPosition($standings)
{

}

function testOutputStanding()
{

    $code = $_GET['name'];


    if (!is_null(isset($code))) {

        $uri = 'https://api.football-data.org/v4/competitions/' . $code . '/standings';
        $reqPref['http']['method'] = 'GET';
        $reqPref['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
        $stream_context = stream_context_create($reqPref);
        $response = file_get_contents($uri, false, $stream_context);
        $standings = json_decode($response, true);


        foreach ($standings['teams'] as $standing) {
            $id = $standing['id'];
            echo "Team: " . $standing['name'] . " ID: " . $id . " Goals: " . $standing['goals'] . "<br>";
        }


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




