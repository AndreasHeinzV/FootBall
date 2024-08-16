<?php

declare(strict_types=1);

namespace App\Controller;

class LogoutController implements Controller
{


    public function load(): void
    {
        $this->handleLogout();
    }

    private function handleLogout(): void
    {
        if (isset($_GET['page']) && $_GET['page'] === "logout") {
            echo "test";
            session_destroy();
            header("location:/");
        }
    }
}