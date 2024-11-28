<?php

declare(strict_types=1);

namespace App\Tests\Components\Shop\Business;

use App\Components\Api\Business\ApiRequesterFacade;
use App\Components\Api\Business\Model\ApiRequester;
use App\Components\Shop\Business\Model\CreateProducts;
use App\Components\Shop\Persistence\Mapper\ProductMapper;
use App\Tests\Fixtures\ApiRequest\ApiRequesterFaker;
use Hoa\Iterator\Mock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateProductsTest extends TestCase
{

    private CreateProducts $createProduct;
    private MockObject $apiRequester;

    protected function setUp(): void
    {
        parent::setUp();
        $productMapper = new ProductMapper();
        $this->apiRequester = $this->createMock(ApiRequester::class);

        $apiRequesterFacade = new ApiRequesterFacade($this->apiRequester);
        $this->createProduct = new CreateProducts($apiRequesterFacade, $productMapper);
    }

    public function testCreateProduct(): void
    {
        $this->apiRequester->method('getTeam')->willReturn([]);
        $product = $this->createProduct->createProducts('2445');
        self::assertEmpty($product);
    }

    public function testCreateProductEmpty(): void
    {
        $this->apiRequester->method('getTeam')->willReturn(['squad' => []]);
        $product = $this->createProduct->createProducts('2445');
        self::assertEmpty($product);
    }

}