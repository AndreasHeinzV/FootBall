<?php

declare(strict_types=1);

namespace App\Tests\Core;

use App\Components\User\Persistence\DTOs\UserDTO;
use App\Core\SessionHandler;
use App\Core\View;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ViewTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testView(): void
    {
        $_ENV['test'] = '';
        $fileSystemLoad = new FilesystemLoader(__dir__ . '/../Fixtures/ViewPages');
        $twig = new Environment($fileSystemLoad);

        $sessionHandlerStub = $this->createStub(SessionHandler::class);
        $sessionHandlerStub->method('getStatus')->willReturn(true);

        $userDTO = new UserDTO('Justus', '', '', '');
        $sessionHandlerStub->method('getUserDTO')->willReturn($userDTO);
        $view = new View($twig, $sessionHandlerStub);
        $view->setTemplate('testPage.twig');
        $view->display();

        $testString = $view->test;
        self::assertStringContainsString('Justus', $testString);
    }
}