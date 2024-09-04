<?php

declare(strict_types=1);

namespace App\Tests\Model\Mapper;

use App\Model\DTOs\ErrorsDTO;
use App\Model\Mapper\ErrorMapper;
use PHPUnit\Framework\TestCase;

class ErrorMapperTest extends TestCase
{

    public function testError(): void
    {
        $mapper = new ErrorMapper();

        $errorDTO = new ErrorsDTO('noName', '', '', '');
        $errorsArray = $mapper->getErrorsData($errorDTO);
        self::assertArrayHasKey('firstNameEmptyError', $errorsArray);
        self::assertSame('noName', $errorsArray['firstNameEmptyError']);
    }

}