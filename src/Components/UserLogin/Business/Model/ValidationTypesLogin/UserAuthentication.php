<?php

declare(strict_types=1);

namespace App\Components\UserLogin\Business\Model\ValidationTypesLogin;

use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Components\UserLogin\Persistence\DTO\UserLoginDto;

readonly class UserAuthentication
{

    public function __construct(private UserBusinessFacadeInterface $userBusinessFacade)
    {
    }

    public function authenticateLogin(UserLoginDto $userLoginDto): bool
    {
        $userDTOFromDB = $this->userBusinessFacade->getUserByMail($userLoginDto->email);

        return password_verify($userLoginDto->password, $userDTOFromDB->password);
    }
}