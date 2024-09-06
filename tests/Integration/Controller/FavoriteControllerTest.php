<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Controller\FavoriteController;
use App\Core\FavoriteHandler;
use App\Core\SessionHandler;
use App\Model\DTOs\UserDTO;
use App\Model\Mapper\UserMapper;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class FavoriteControllerTest extends TestCase
{

    public function testAdd(): void
    {
        $view = new ViewFaker();
        $userMapper = new UserMapper();
        $userDTO = new UserDTO('', '', '', '');
        $favoriteHandler = new FavoriteHandler($userDTO);
        $sessionHandler = new SessionHandler($userMapper);
        $controller = new FavoriteController($sessionHandler, $favoriteHandler);


        $controller->load($view);
        $parameters = $view->getParameters();
        $template = $view->getTemplate();


        self::assertSame('favorite.twig', $template);
        self::assertNotEmpty($parameters);
    }
}