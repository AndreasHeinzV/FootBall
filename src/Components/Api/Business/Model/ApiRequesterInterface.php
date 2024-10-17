<?php

namespace App\Components\Api\Business\Model;

interface ApiRequesterInterface
{
    public function parRequest($url): array;
}