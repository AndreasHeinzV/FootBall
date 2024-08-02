<?php
function request()
{
    $uri = 'https://api.football-data.org/v2/competitions/';
    $reqPrefs['http']['method'] = 'GET';
    $reqPrefs['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
    $stream_context = stream_context_create($reqPrefs);
    $response = file_get_contents($uri, false, $stream_context);
    $matches = json_decode($response, true);
    print_r($matches);
    /*
foreach ($matches->competitions as $competition) {
     var_dump(compact('competition'));

}*/
}
request();

/*
query = $_GET['query'];
$test  = $_GET[''];

if ($query === 'XY')
{
    # ZEIG SEITE VON SPIELER XY AN
}
*/
?>
<a href= "../">back </a>


<p>HDUWASUDhuQWd</p>

<ul></ul> # LISTE
<h1></h1> # TITLE

