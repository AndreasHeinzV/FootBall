<?php

namespace App\Model;


class ApiRequester implements ApiRequesterInterface
{
    private $apiKey;

//todo get Api key from jsonfile out of project
    public function __construct()
    {
        $this->apiKey = "f08428cacebe4e639816224794f01bd5";
    }

    public function parRequest($url): array
    {
        $reqPref['http']['method'] = 'GET';
        $reqPref['http']['header'] = 'X-Auth-Token: ' . $this->apiKey;
        $stream_context = stream_context_create($reqPref);
        $response = file_get_contents($url, false, $stream_context);

        // need this to get testData
        //  $filename = str_replace(['https://api.football-data.org/v4/', '/'], [''], $url);
        //  file_put_contents(__DIR__ . '/' . $filename . '.json', $response);
        return json_decode($response, true);
    }
}