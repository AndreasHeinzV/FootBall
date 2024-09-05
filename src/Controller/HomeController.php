<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\SessionHandler;
use App\Core\View;
use App\Core\ViewInterface;
use App\Model\FootballRepository;

class HomeController implements Controller
{
    private FootballRepository $repository;
    public SessionHandler $sessionHandler;

    public function __construct(FootballRepository $repository,  SessionHandler $sessionHandler)
    {
        $this->repository = $repository;
        $this->sessionHandler = $sessionHandler;
    }


    public function load(ViewInterface $view): void
    {
        /*
        if (!isset($_GET['page'])  && $this->sessionHandler->getStatus() === true) {

          //  $this->value['userName'] =$_SESSION['userName'] ?? '';
           // $this->value['status'] = true;
        }
        */
       $this->setupView($view);
    }

    private function setupView(ViewInterface $view): void
    {
        $view->setTemplate('home.twig');
        //var_export($this->repository->getLeagues());
       // $view->addParameter('userName', $this->value['userName'] ?? '');
        $view->addParameter('leagues', $this->repository->getLeagues() ?? []);
     //   $view->addParameter('userDto', $this->sessionHandler->getUserDto());
     //$view->addParameter('status', $this->value['status'] ?? false);
    }
}