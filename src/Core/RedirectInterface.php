<?php

namespace App\Core;

interface RedirectInterface
{
    public function to(string $location): void;
}