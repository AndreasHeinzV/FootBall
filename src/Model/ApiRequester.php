<?php

namespace App\Model;


class ApiRequester
{
    private $apiKey;

//todo get Api key from jsonfile out of project
    function __construct()
    {
        $this->apiKey = "f08428cacebe4e639816224794f01bd5";
    }

    public function parRequest($url): array
    {
        $reqPref['http']['method'] = 'GET';
        $reqPref['http']['header'] = 'X-Auth-Token: ' . $this->apiKey;
        $stream_context = stream_context_create($reqPref);
        $response = file_get_contents($url, false, $stream_context);

        return json_decode($response, true);
    }
}