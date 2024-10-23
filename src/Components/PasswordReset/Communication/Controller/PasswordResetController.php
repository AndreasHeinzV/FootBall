<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Communication\Controller;

use App\Components\PasswordReset\Business\PasswordResetBusinessFacadeInterface;
use App\Components\PasswordReset\Persistence\DTOs\MailDTO;
use App\Components\PasswordReset\Persistence\DTOs\ResetDTO;
use App\Core\Redirect;
use App\Core\ViewInterface;


readonly class PasswordResetController
{
    public function __construct(
        private PasswordResetBusinessFacadeInterface $passwordResetBusinessFacade,
        private Redirect $redirect
    ) {
    }

    public function load(ViewInterface $view): void
    {
        $result = null;
        $view->setTemplate('404.twig');

        if (!isset($_GET['email'], $_GET['ts'], $_GET['actionId'])) {
            $mailDTO = new MailDTO();
            $mailDTO->email = $_GET['email'];
            $mailDTO->timestamp = $_GET['ts'];
            $mailDTO->actionId = $_GET['actionId'];
            $result = $this->passwordResetBusinessFacade->checkInputsForIntegrity($mailDTO);
            if (!$result) {
                $this->redirect->to('404.twig');
            }

            $view->setTemplate('password-reset.twig');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['passwordReset'] = 'push') {
            $result = $this->passwordResetBusinessFacade->resetUserPassword($mailDTO);
            if (!$result instanceof ResetDTO) {
                $this->redirect->to('');
            }
            $view->setTemplate('password-reset.twig');
        }

        $view->addParameter('resetErrorDto', $result);
    }

}