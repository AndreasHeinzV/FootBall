<?php

namespace App\Model;

interface ApiRequesterInterface
{
    public function parRequest($url): array;
}