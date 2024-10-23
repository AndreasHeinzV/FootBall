<?php

declare(strict_types=1);

namespace App\Tests\Unit\Mailcatcher;

use App\Components\PasswordReset\Business\Model\PasswordFailed\EmailDispatcher;
use App\Components\PasswordReset\Persistence\DTOs\MailDTO;
use PHPMailer\PHPMailer\PHPMailer;
use PHPUnit\Framework\TestCase;

class EmailDispatcherTest extends TestCase
{
    private EmailDispatcher $mailer;

    private MailDTO $testMailDTO;

    protected function setUp(): void
    {
        parent::setUp();
        $phpMailer = new PhpMailer();
        $this->mailer = new EmailDispatcher($phpMailer);
        $this->testMailDTO = new MailDTO();
    }

    public function testSend(): void
    {
        $this->testMailDTO->email = "test@test.com";
        $this->testMailDTO->message = 'hello';
        $status = $this->mailer->sendMail($this->testMailDTO);
        self::assertTrue($status);
    }

    public function testSendFailNoMail(): void
    {
        $this->testMailDTO->message = 'hello';
        $status = $this->mailer->sendMail($this->testMailDTO);
        self::assertFalse($status);
    }

    public function testSendFailNoMessage(): void{
        $this->testMailDTO->email = "test@test.com";
        $status = $this->mailer->sendMail($this->testMailDTO);
        self::assertFalse($status);
    }
}