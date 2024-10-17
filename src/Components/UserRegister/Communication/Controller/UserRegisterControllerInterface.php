<?php

namespace App\Components\UserRegister\Communication\Controller;

use App\Core\ViewInterface;

interface UserRegisterControllerInterface
{
    public function load(ViewInterface $view): void;
}