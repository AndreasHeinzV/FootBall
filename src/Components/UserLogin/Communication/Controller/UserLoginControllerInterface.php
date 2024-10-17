<?php

namespace App\Components\UserLogin\Communication\Controller;

use App\Core\ViewInterface;

interface UserLoginControllerInterface
{

    public function load(ViewInterface $view): void;
}