<?php

declare(strict_types=1);

namespace App\Tests\Components\Shop\Business;

use App\Components\Shop\Business\Model\CalculatePrice;
use App\Components\Shop\Persistence\DTOs\ProductDto;
use PHPUnit\Framework\TestCase;

class CalculatePriceTest extends TestCase
{
    private CalculatePrice $calculatePrice;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculatePrice = new CalculatePrice();
    }


    public function testCalculatePriceXXl(): void
    {
        $productDto = new ProductDto('', '', 'soccerJersey', 'XXl', null, '', 1);
        $productDto = $this->calculatePrice->calculateProductPrice($productDto);

        self::assertNotEmpty($productDto->price);
        self::assertSame(34.99, $productDto->price);
    }

    public function testCalculatePriceXL(): void
    {
        $productDto = new ProductDto('', '', 'soccerJersey', 'Xl', null, '', 1);
        $productDto = $this->calculatePrice->calculateProductPrice($productDto);

        self::assertNotEmpty($productDto->price);
        self::assertSame(29.99, $productDto->price);
    }

    public function testCalculatePriceM(): void
    {
        $productDto = new ProductDto('', '', 'soccerJersey', 'M', null, '', 1);
        $productDto = $this->calculatePrice->calculateProductPrice($productDto);

        self::assertNotEmpty($productDto->price);
        self::assertSame(19.99, $productDto->price);
    }

    public function testCalculatePriceS(): void
    {
        $productDto = new ProductDto('', '', 'soccerJersey', 'S', null, '', 1);
        $productDto = $this->calculatePrice->calculateProductPrice($productDto);

        self::assertNotEmpty($productDto->price);
        self::assertSame(14.99, $productDto->price);
    }

    public function testCalculateProductPriceNoSize(): void
    {
        $productDto = new ProductDto('', '', 'soccerJersey', null, null, '', 1);
        $productDto = $this->calculatePrice->calculateProductPrice($productDto);
        self::assertNull($productDto->price);
    }

    public function testCalculateProductPriceCup(): void
    {
        $productDto = new ProductDto('', '', 'cup', null, null, '', 1);
        $productDto = $this->calculatePrice->calculateProductPrice($productDto);
        self::assertSame(9.99, $productDto->price);
    }

    public function testCalculateProductPriceScarf(): void
    {
        $productDto = new ProductDto('', '', 'scarf', null, null, '', 1);
        $productDto = $this->calculatePrice->calculateProductPrice($productDto);
        self::assertSame(19.99, $productDto->price);
    }

    public function testCalculateProductPriceJerseyWrongSize(): void
    {
        $productDto = new ProductDto('', '', 'soccerJersey', 'wegw', null, '', 1);
        $productDto = $this->calculatePrice->calculateProductPrice($productDto);
        self::assertSame(-1.0, $productDto->price);
    }

    public function testCalculateProductPriceCupSizeLowerCase(): void
    {
        $productDto = new ProductDto('', '', 'soccerJersey', 'xs', null, '', 1);
        $productDto = $this->calculatePrice->calculateProductPrice($productDto);
        self::assertSame(9.99, $productDto->price);
    }

    public function testCalculateProductPriceCupSizeNumber(): void
    {
        $productDto = new ProductDto('', '', 'soccerJersey', '434', null, '', 1);
        $productDto = $this->calculatePrice->calculateProductPrice($productDto);
        self::assertSame(-1.0, $productDto->price);
    }

}