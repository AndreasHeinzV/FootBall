<?php

namespace App\Components\Pages\Business\Communication\Controller;

use App\Core\ViewInterface;

interface NoPageControllerInterface
{
    public function load(ViewInterface $view): void;
}