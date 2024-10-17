<?php

namespace App\Components\User\Persistence\Mapper;

use App\Components\User\Persistence\DTOs\ErrorsDTO;

interface ErrorMapperInterface
{
    public function createErrorDTO(array $errors): ErrorsDTO;
    public function ErrorDTOToArray(ErrorsDTO $errorsDTO): array;
}