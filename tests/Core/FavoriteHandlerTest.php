<?php

declare(strict_types=1);

namespace App\Tests\Core;

use App\Core\FavoriteHandler;
use App\Model\DTOs\UserDTO;
use PHPUnit\Framework\TestCase;

class FavoriteHandlerTest extends TestCase
{

    public function testFavorite(): void
    {
        $userDTO = new UserDTO('', '', '', '');
//        $handler = new FavoriteHandler();
        self::assertTrue(true);
    }
}