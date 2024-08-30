<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\ViewInterface;


class LogoutController implements Controller
{

    public function load(ViewInterface $view): void
    {
        $this->handleLogout();

    }

    private function handleLogout(): void
    {
        session_destroy();
        header("location:/");
    }
}