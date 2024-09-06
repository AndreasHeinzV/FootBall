<?php

declare(strict_types=1);

namespace App\Tests\Core;

use App\Core\SessionHandler;
use App\Core\View;
use App\Model\DTOs\UserDTO;
use App\Model\Mapper\UserMapper;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use stdClass;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ViewTest extends TestCase
{

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