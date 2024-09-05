<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Core\Redirect;
use App\Core\RedirectInterface;

class RedirectSpy implements RedirectInterface
{
    public string $location = '';
    public function to(string $location): void
    {
        $this->location = $location;
    }
}