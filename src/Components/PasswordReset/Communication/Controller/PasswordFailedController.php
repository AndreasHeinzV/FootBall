<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Communication\Controller;

use App\Components\PasswordReset\Business\PasswordResetBusinessFacadeInterface;
use App\Core\RedirectInterface;
use App\Core\ViewInterface;

readonly class PasswordFailedController
{

    public function __construct(
        private PasswordResetBusinessFacadeInterface $passwordResetBusinessFacade,
        private RedirectInterface $redirect
    ) {
    }

    public function load(ViewInterface $view): void
    {
        $status = null;
        if (($_SERVER['REQUEST_METHOD'] === 'POST') && $_POST['password-failed'] === 'push') {
            $status = $this->passwordResetBusinessFacade->sendPasswordResetEmail($_POST['email']);
        }
        $view->addParameter('passwordStatus', $status);
        $view->setTemplate('password-failed.twig');

        if ($status) {
            $this->redirect->to('/');
        }
    }
}