<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Core\View;

class ViewFaker extends View
{
    public function __construct()
    {
    }

    public function getTemplate(): string
    {
        return $this->template;
    }
    public function getParameters(): array
    {
        return $this->parameters;
    }
}