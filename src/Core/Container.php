<?php

declare(strict_types=1);

namespace App\Core;

class Container
{
    /**
     * @var array<string, object>
     */
    private array $services = [];

    /**
     * @param string $name
     * @return object
     * @throws \InvalidArgumentException
     */

    public function get(string $name): object
    {
        return $this->services[$name];
    }

    /**
     * @param string $name
     * @param object $instance
     * @return void
     */
    public function set(string $name, object $instance): void
    {
        $this->services[$name] = $instance;
    }
}