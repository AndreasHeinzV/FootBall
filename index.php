<?php

echo 'page:';
var_dump($_GET['id']);

echo '<br>id:';
var_dump( $_GET['id']);


echo '<br>';



echo '<a href="/index.php?page=hallo&id=123">Hallo</a><br>';


die();

function request()
{
    $uri = 'https://api.football-data.org/v2/competitions/';
    $reqPrefs['http']['method'] = 'GET';
    $reqPrefs['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
    $stream_context = stream_context_create($reqPrefs);
    $response = file_get_contents($uri, false, $stream_context);
    $matches = json_decode($response, true);



    foreach ($matches['competitions'] as $competition) {
        var_dump($competition['id'], $competition['name']);
        ///index.php?page=competition&id=$competition['id'],
        //<a href=index?$competition=><$competition['name']</a>;
        die();
         echo $competition['name'] ;
        echo $_GET['name']. "<br>";
    }

    var_dump($matches);
   /* foreach ($matches['competitions'] as $competition) {
        if ($competition['name'] ==='Bundesliga'){
            echo $competition['name'];
        }

    }
*/
}


?>
<?php


function initiate()
{
    $uri = 'https://api.football-data.org/v2/competitions/';
    $reqPrefs['http']['method'] = 'GET';
    $reqPrefs['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
    $stream_context = stream_context_create($reqPrefs);
    $response = file_get_contents($uri, false, $stream_context);
    $matches = json_decode($response, true);
  $length =  getLength($matches);

    request();
}
function getLength()
{
    return 179;
}

?>

<?php
function requestFor($number, $matches){
foreach ($matches['competitions'] as $competition) {

        echo '<a href="dasdasdsa?date=' . $competition['name'] . '">' . $competition['name'] . '</a><br>';




}}
?>


<?php function test(){
     $uri = 'https://api.football-data.org/v2/competitions/PL/standings';
    $reqPrefs['http']['method'] = 'GET';
    $reqPrefs['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
    $stream_context = stream_context_create($reqPrefs);
    $response = file_get_contents($uri, false, $stream_context);
    $matches = json_decode($response, true);
    var_dump($matches);
}
?>

<a href="page=competion&name=PL">Premier League<?php ?></a>
<?php
request();

$_GET['name'] = 'PL';
?>


