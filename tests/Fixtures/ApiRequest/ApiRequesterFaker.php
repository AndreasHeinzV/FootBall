<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\ApiRequest;

use App\Model\ApiRequesterInterface;

class ApiRequesterFaker implements ApiRequesterInterface
{
    public function parRequest($url): array
    {
        $filename = str_replace(['https://api.football-data.org/v4/', '/'], [''], $url);
        $path = __DIR__ . '/cache/' . $filename . '.json';

        if (!file_exists($path)) {

            return [];
        }

        return json_decode(file_get_contents($path), true);
    }


}