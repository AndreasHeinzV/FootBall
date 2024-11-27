<?php

declare(strict_types=1);

namespace App\Components\Shop\Business\Model;

use App\Components\Api\Business\ApiRequesterFacade;
use App\Components\Shop\Persistence\Mapper\ProductMapper;

class CreateProducts
{

    public function __construct(private ApiRequesterFacade $apiRequesterFacade, private ProductMapper $productMapper)
    {
    }

    public function createProducts(string $teamId): array
    {
        $teamDtoArray = $this->apiRequesterFacade->getTeam($teamId);

        $squad = $teamDtoArray['squad'];
        if (empty($squad)) {
            return [];
        }
        $soccerImageLink = 'https://cdn.media.amplience.net/i/frasersdev/37966518_o?fmt=auto&upscale=false&w=345&h=345&sm=c&$h-ttl$';
        $teamName = $teamDtoArray['teamName'];
        $productsArray = [];

        foreach ($squad as $team) {
            $productsArray[] = $this->productMapper->createProductDto(
                'soccerJersey',
                $teamName,
                $team->name . ' soccer jersey',
                $soccerImageLink,
                null,
                null,
                null
            );
        }
        $cupImage = 'https://t4.ftcdn.net/jpg/00/72/09/65/360_F_72096563_ei7KGRxgaKIX3GU2gFKWS9sxCrudCe4g.jpg';
        $scarfImage = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTg_Puj8YY6Yd4DIS230gL-k8IHVCG9T4QjZQ&s';
        $productsArray[] = $this->productMapper->createProductDto('cup', $teamName , 'cup', $cupImage, null, null, null);
        $productsArray[] = $this->productMapper->createProductDto(
            'scarf',
            $teamName,
            $teamName . ' scarf',
            $scarfImage,
            null,
            null,
            null
        );
        return $productsArray;
    }


}