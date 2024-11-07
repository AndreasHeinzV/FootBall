<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Business\Model\PasswordFailed;

use App\Components\PasswordReset\Persistence\DTOs\MailDTO;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class EmailDispatcher implements EmailDispatcherInterface
{

    public function __construct(private PHPMailer $mail)
    {
        $this->setup();
    }

    private function setup(): void
    {
        $this->mail->isSMTP();
        $this->mail->Host = 'localhost';
        $this->mail->Port = 1025;
        $this->mail->SMTPAuth = false;
        $this->mail->SMTPSecure = 'false';
        $this->mail->setFrom('FootballApi@service.com', 'EmailDispatcher');
        $this->mail->Subject = 'Test Mail';
        $this->mail->isHTML();
    }

    /**
     * @throws Exception
     */
    public function sendMail(MailDTO $mailDTO): bool
    {
        $this->mail->addAddress($mailDTO->email);
        $this->mail->Body = $mailDTO->message;

        if ($mailDTO->email !== null) {
            return $this->mail->send();
        }
        return false;
    }
}