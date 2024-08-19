<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\ViewInterface;
use Twig\Environment;

class LogoutController implements Controller
{

    public function load(ViewInterface $view): array
    {
        $this->handleLogout();
        return [];
    }

    private function handleLogout(): void
    {
        session_destroy();
        header("location:/");
    }
}