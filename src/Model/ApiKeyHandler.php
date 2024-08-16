<?php

declare(strict_types=1);

namespace App\Model;

class ApiKeyHandler
{

    //TODO for later...
    public function getApiKey(): string
    {
    $apiKeyArray = json_decode(file_get_contents('/home/andreasheinz/ApiKey.json'), true);
    return $apiKeyArray['apiKey'];
    }
    function setApiKey(): void
    {
        $apiKey = ["apiKey" => "f08428cacebe4e639816224794f01bd5"];
        file_put_contents(__DIR__. '/../apiKey.json', json_encode($apiKey, JSON_PRETTY_PRINT));

    }

}