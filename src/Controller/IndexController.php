<?php
declare(strict_types=1);
namespace App\Controller;
use App\Model\FootballRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class IndexController
{
    private FootballRepository $repository;
    private Environment $twig;
    private  array $value;
    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../View');
        $this->twig = new Environment($loader);
        $this->value = [];
        $this->repository = new FootballRepository();
    }

    public function loadIndex(): void
    {
        if (!isset($_GET['page']) && !isset($_GET['code'])) {
            $this->value['leagues'] = $this->repository->getLeagues();
        }


        $loginStatus = false;
        $sessionUsername = '';


        if (isset($_SESSION['loginStatus']) && $_SESSION['loginStatus']) {
            $sessionUsername = $_SESSION['userName'];
            $loginStatus = $_SESSION['loginStatus'];
        }
        if (isset($_POST['login'])) {
            header("Location: /login.php");
        }


        $this->value['userName'] = $sessionUsername;
        $this->value['status'] = $loginStatus;
    }


    public function renderIndex(): void
    {
        echo $this->twig->render('home.twig', $this->value);
    }
}