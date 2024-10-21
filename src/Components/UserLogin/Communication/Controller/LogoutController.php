<?php

declare(strict_types=1);

namespace App\Components\UserLogin\Communication\Controller;


use App\Core\RedirectInterface;
use App\Core\SessionHandlerInterface;
use App\Core\ViewInterface;


readonly class LogoutController implements UserLoginControllerInterface
{

    public function __construct(
        private SessionHandlerInterface $sessionHandler,
        private RedirectInterface $redirect
    ) {
    }

    public function load(ViewInterface $view): void
    {
        if (!(session_status() === PHP_SESSION_NONE)) {
            session_destroy();
            $this->sessionHandler->stopSession();
            $this->redirect->to('');
        }
    }
}