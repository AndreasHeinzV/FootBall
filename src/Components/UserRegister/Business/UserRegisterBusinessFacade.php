<?php

declare(strict_types=1);

namespace App\Components\UserRegister\Business;

use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\UserRegister\Business\Model\Register;
use App\Components\UserRegister\Business\Model\RegisterInterface;
use App\Components\UserRegister\Persistence\DTO\UserRegisterDto;


readonly class UserRegisterBusinessFacade implements UserRegisterBusinessFacadeInterface
{

    public function __construct(private RegisterInterface $register)
    {
    }

    public function registerUserNew(UserRegisterDto $userRegisterDto): ?ErrorsDTO
    {
        return $this->register->execute($userRegisterDto);
    }
}