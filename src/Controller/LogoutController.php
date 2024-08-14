<?php
declare(strict_types=1);
namespace App\Controller;

class LogoutController
{
    public function __construct()
    {
    }

    public function logout(): void
    {

        if (isset($_GET['page']) && $_GET['page'] === "logout") {
            echo "test";
            session_destroy();
            header("location:/");
        }
    }

}