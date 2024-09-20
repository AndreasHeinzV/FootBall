<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\ViewInterface;

class NoPageController implements Controller
{

    public function load(ViewInterface $view): void
    {
      $view->setTemplate('404.twig');
    }
}