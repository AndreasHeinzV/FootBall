<?php

declare(strict_types=1);

namespace App\Components\Shop\Business;

use App\Components\Shop\Business\Model\CreateProducts;

readonly class ProductBusinessFacade
{
    public function __construct(private CreateProducts $createProducts)
    {
    }

    public function getClubProducts(string $teamId): array
    {
        return $this->createProducts->createProducts($teamId);
    }

}