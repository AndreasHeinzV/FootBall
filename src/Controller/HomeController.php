<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Core\ViewInterface;
use App\Model\FootballRepository;

class HomeController implements Controller
{
    private FootballRepository $repository;
    private array $value;


    public function __construct(FootballRepository $repository)
    {
        $this->repository = $repository;
        $this->value = [];
    }


    public function load(ViewInterface $view): void
    {
        if (!isset($_GET['page']) && isset($_SESSION['loginStatus']) && $_SESSION['loginStatus'] === true) {
            $this->value['userName'] =$_SESSION['userName'] ?? '';
            $this->value['status'] = true;
        }
       $this->setupView($view);
    }

    private function setupView(ViewInterface $view): void
    {
        $view->setTemplate('home.twig');
        //var_export($this->repository->getLeagues());
        $view->addParameter('userName', $this->value['userName'] ?? '');
        $view->addParameter('leagues', $this->repository->getLeagues() ?? []);
        $view->addParameter('status', $this->value['status'] ?? false);
    }
}