<?php

declare(strict_types=1);

namespace App\Controller;

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


    public function load(ViewInterface $view): array
    {
        if (!isset($_GET['page'])) {
            $this->value['leagues'] = $this->repository->getLeagues();
            $this->value['status'] = false;
            //  $view->addParameter('leagues', $this->repository->getLeagues());
            //$view->addParameter('status', false);
            $sessionUsername = $_SESSION['userName'] ?? '';
            if (isset($_SESSION['loginStatus']) && $_SESSION['loginStatus'] === true) {
                $this->value['userName'] = $sessionUsername;
                $this->value['status'] = true;
                // $view->addParameter('username', $sessionUsername);
                //$view->addParameter('status', true);
            }
            //echo $this->twig->render('home.twig', $this->value);
        }
        return $this->value;
    }
}