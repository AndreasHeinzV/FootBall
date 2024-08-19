<?php

declare(strict_types=1);
namespace App\Controller;

use App\Core\ViewInterface;

interface Controller
{
    public function load(ViewInterface $view): array;

}