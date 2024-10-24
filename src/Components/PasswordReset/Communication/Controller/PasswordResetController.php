<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Communication\Controller;

use App\Components\PasswordReset\Business\PasswordResetBusinessFacadeInterface;
use App\Components\PasswordReset\Persistence\DTOs\ActionDTO;
use App\Components\PasswordReset\Persistence\DTOs\MailDTO;
use App\Components\PasswordReset\Persistence\DTOs\ResetDTO;
use App\Core\Redirect;
use App\Core\RedirectInterface;
use App\Core\ViewInterface;


readonly class PasswordResetController
{
    public function __construct(
        private PasswordResetBusinessFacadeInterface $passwordResetBusinessFacade,
        private RedirectInterface $redirect
    ) {
    }

    public function load(ViewInterface $view): void
    {
        $result = null;
        $view->setTemplate('404.twig');

        if (isset($_GET['ts'], $_GET['actionId'])) {
            $actionDTO = new ActionDTO();
            $actionDTO->timestamp = $_GET['ts'];
            $actionDTO->actionId = $_GET['actionId'];
            $result = $this->passwordResetBusinessFacade->checkInputsForIntegrity($actionDTO);
            if (!$result) {
                $this->redirect->to('404.twig');
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['passwordReset'] = 'push') {
            $resetDTO = new ResetDTO();
            $resetDTO->FirstPassword = $_POST['firstPassword'];
            $resetDTO->SecondPassword = $_POST['secondPassword'];

            $result = $this->passwordResetBusinessFacade->resetUserPassword($_GET['actionId'], $resetDTO);
            if (!$result instanceof ResetDTO) {
                $this->redirect->to('');
            }
        }
        $view->setTemplate('password-reset.twig');
        $view->addParameter('resetErrorDto', $result);
    }

}