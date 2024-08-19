<?php

declare(strict_types=1);

namespace App\Core;

class Container
{
    private array $services = [];
    public function get(string $name): object
    {
        return $this->services[$name];
    }

    public function set(string $name, object $instance): void
    {
        $this->services[$name] = $instance;
    }
}