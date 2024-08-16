<?php

declare(strict_types=1);
namespace App\Controller;
use App\Model\FootballRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


class PlayerController implements Controller
{
    private FootballRepository $repository;
    private Environment $twig;
    private array $value;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../View');
        $this->twig = new Environment($loader);
        $this->repository = new FootballRepository();
        $this->value = [];
    }

    public function load(): void
    {
        $id = $_GET['id'];

        if (isset($id) && $_GET['page'] === "player") {
            $playerArray = $this->repository->getPlayer($id);
            $playerName = array_shift($playerArray);
            $this->value['playerName'] = $playerName;
            $this->value['playerData'] = $playerArray;

        }
    $this->renderPlayer();
    }

    private function renderPlayer(): void
    {
        echo $this->twig->render('player.twig', $this->value);
    }
}