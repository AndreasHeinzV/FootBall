<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Communication\Controller;

use App\Components\PasswordReset\Business\PasswordResetBusinessFacadeInterface;
use App\Components\PasswordReset\Persistence\DTOs\ActionDTO;
use App\Components\PasswordReset\Persistence\DTOs\ResetDTO;
use App\Components\PasswordReset\Persistence\DTOs\ResetErrorDTO;
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
        $resetErrorDto = null;
        $view->setTemplate('error.twig');

        if (isset($_GET['ts'], $_GET['actionId'])) {

            $actionDTO = new ActionDTO();
            $actionDTO->timestamp = (int)$_GET['ts'];
            $actionDTO->actionId = $_GET['actionId'];
            $result = $this->passwordResetBusinessFacade->checkInputsForIntegrity($actionDTO);

            if ($result) {
                $view->setTemplate('password-reset.twig');
                //    $this->redirect->to('?page=error');
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['passwordReset'] = 'push') {
            $resetDTO = new ResetDTO();
            $resetDTO->FirstPassword = $_POST['firstPassword'];
            $resetDTO->SecondPassword = $_POST['secondPassword'];

            $resetErrorDto = $this->passwordResetBusinessFacade->resetUserPassword($_GET['actionId'], $resetDTO);
            if (!$resetErrorDto instanceof ResetErrorDTO) {
                $this->redirect->to('');
            }
        }
     //   $view->setTemplate('password-reset.twig');
        $view->addParameter('resetErrorDto', $resetErrorDto);
        $view->addParameter('resetAllowed', $result);
    }

}