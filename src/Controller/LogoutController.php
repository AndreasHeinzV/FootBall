<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\RedirectInterface;
use App\Core\SessionHandler;
use App\Core\ViewInterface;


readonly class LogoutController implements Controller
{

    public function __construct(
        private SessionHandler $sessionHandler,
        private RedirectInterface $redirect
    ) {
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