<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\ViewInterface;
use App\Model\FootballRepository;
use App\Model\FootballRepositoryInterface;
use App\Model\UserRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class LeaguesController implements Controller
{
    private FootballRepository $repository;
    private Environment $twig;

    private UserRepository $userRepository;
    public function __construct(Environment $twig, FootballRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->twig = $twig;

    }

    public function load(ViewInterface $view): array
    {
        /* if (!isset($_GET['name'])) {
             throw new \RuntimeException("No name specified");
         }
 */
        if (isset($_GET['name'])) {
            $code = $_GET['name'];
            $teamsArray = $this->repository->getCompetition($code);
            $value['teams'] = $teamsArray;
            return $value;
          //  echo $this->twig->render('competitions.twig', $value);
        }
        return [];
    }
}