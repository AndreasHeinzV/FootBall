<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\RedirectInterface;
use App\Core\SessionHandler;
use App\Core\ViewInterface;


class LogoutController implements Controller
{
    public  SessionHandler $sessionHandler;
    public RedirectInterface $redirect;

    public function __construct(SessionHandler $sessionHandler, RedirectInterface $redirect)
    {
        $this->sessionHandler = $sessionHandler;
        $this->redirect = $redirect;
    }
    public function load(ViewInterface $view): void
    {

        $this->handleLogout();

    }

    private function handleLogout(): void
    {
        if (!(session_status() === PHP_SESSION_NONE)) {
            session_destroy();
            $this->sessionHandler->stopSession();
            $this->redirect->to('');
        }

    }
}