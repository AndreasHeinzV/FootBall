<?php

namespace App\Components\Football\Communication\Controller;

use App\Core\ViewInterface;

interface FootballControllerInterface
{
public function load(ViewInterface $view): void;
}