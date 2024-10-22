<?php

declare(strict_types=1);

namespace App\Tests\Unit\Mailcatcher;

use PHPMailer\PHPMailer\PHPMailer;
use PHPUnit\Framework\TestCase;

class Mailcatcher extends TestCase
{

    public function testSendMail(): void{

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'localhost'; // Use the service name as the hostname
        $mail->Port = 1025; // Default Mailcatcher SMTP port
        $mail->SMTPAuth = false;
        $mail->SMTPSecure = false;

        $mail->setFrom('from@example.com', 'Mailer');
        $mail->addAddress('recipient@example.com');
        $mail->Subject = 'Test Mail';
        $mail->Body    = 'This is a test mail sent to Mailcatcher.';

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
        self::assertTrue($mail->send());
    }


}