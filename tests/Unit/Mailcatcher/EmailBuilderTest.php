<?php

declare(strict_types=1);

namespace App\Tests\Unit\Mailcatcher;

use App\Components\PasswordReset\Business\Model\PasswordFailed\EmailBuilder;
use App\Components\PasswordReset\Persistence\DTOs\MailDTO;
use PHPUnit\Framework\TestCase;

class EmailBuilderTest extends TestCase
{

    private EmailBuilder $buildMail;

    private MailDTO $mailDTO;


    protected function setUp(): void
    {
        parent::setUp();
        $this->buildMail = new EmailBuilder();
        $this->mailDTO = new MailDTO();
    }

    public function testBuildMail(): void
    {
        self::assertNull($this->mailDTO->message);
        $this->mailDTO = $this->buildMail->buildMail($this->mailDTO);
        self::assertIsString($this->mailDTO->message);
    }

}