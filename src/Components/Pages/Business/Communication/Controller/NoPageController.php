<?php

declare(strict_types=1);

namespace App\Components\Pages\Business\Communication\Controller;


use App\Core\ViewInterface;

class NoPageController implements NoPageControllerInterface
{

    public function load(ViewInterface $view): void
    {
      $view->setTemplate('404.twig');
    }
}