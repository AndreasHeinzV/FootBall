<?php

declare(strict_types=1);

namespace App\Components\Shop\Business\Model;

use App\Components\Api\Business\ApiRequesterFacade;

class CreateProducts
{

    public function __construct(private ApiRequesterFacade $apiRequesterFacade)
    {
    }

    public function createProducts(string $teamId): array
    {
        $teamDtoArray = $this->apiRequesterFacade->getTeam($teamId);
        if (empty($teamDtoArray)) {
            return [];
        }
        $squad = $teamDtoArray['squad'];
        $teamName = $teamDtoArray['teamName'];
        $productsArray = [];

        foreach ($squad as $team) {
            $productDto = new ProductDto();
            $productDto->imageLink = 'imageLink';
            $productDto->name = $team->name . ' soccer jersey';
            $productDto->category = 'soccerJersey';
            $productDto->link = $this->createProductLink(
                $productDto->category,
                $productDto->name,
                $productDto->imageLink
            );
            $productsArray[] = $productDto;
        }

        $productDto = new ProductDto();
        $productDto->imageLink = 'imageLink';
        $productDto->name = $teamName . ' cup';
        $productDto->category = 'cup';
        $productDto->link = $this->createProductLink($productDto->category, $productDto->name, $productDto->imageLink);
        $productsArray[] = $productDto;

        $productDtoScarf = new ProductDto();
        $productDtoScarf->imageLink = 'imageLink';
        $productDtoScarf->name = $teamName . ' scarf';
        $productDtoScarf->category = 'scarf';
        $productDtoScarf->link = $this->createProductLink('scarf', $productDtoScarf->name, $productDtoScarf->imageLink);

        $productsArray[] = $this->createProductDto('scarf', $teamName. ' scarf', $productDtoScarf->imageLink);
//finish this
        return $productsArray;
    }

    public function createProductLink(string $category, string $name, string $imageLink): string
    {
        return 'http://localhost:8000/index.php?page=details&category=' . $category . '&imageLink=' . $imageLink . '&name=' . $name;
    }

    public function createProductDto(string $category, string $name, string $imageLink): ProductDto
    {
        $productDto = new ProductDto();
        $productDto->imageLink = $imageLink;
        $productDto->name = $name;
        $productDto->category = $category;
        $productDto->link = $this->createProductLink($category, $productDto->name, $productDto->imageLink);
        return $productDto;
    }
}