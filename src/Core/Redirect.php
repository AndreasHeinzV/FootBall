<?php

declare(strict_types=1);

namespace App\Core;

class Redirect implements RedirectInterface
{

    private string $url = 'http://localhost:8000/';

    public function to(string $location): void
    {
        header("Location: $this->url$location");
    }
}