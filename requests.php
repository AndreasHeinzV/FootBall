<?php
function request()
{
    $uri = 'https://api.football-data.org/v2/competitions/PL/matches/?matchday=22';
    $reqPrefs['http']['method'] = 'GET';
    $reqPrefs['http']['header'] = 'X-Auth-Token: f08428cacebe4e639816224794f01bd5';
    $stream_context = stream_context_create($reqPrefs);
    $response = file_get_contents($uri, false, $stream_context);
    $matches = json_decode($response);

  return $matches;

}
$matches = request();

$query = $_GET['query'];

if ($query === 'XY')
{
    # ZEIG SEITE VON SPIELER XY AN
}

?>
<p>HDUWASUDhuQWd</p>