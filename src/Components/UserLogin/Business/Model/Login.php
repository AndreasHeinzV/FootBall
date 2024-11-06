<?php

declare(strict_types=1);

namespace App\Components\UserLogin\Business\Model;

use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Components\User\Persistence\DTOs\ErrorsDTO;

use App\Components\UserLogin\Persistence\DTO\UserLoginDto;
use App\Core\SessionHandlerInterface;

readonly class Login implements LoginInterface
{

    public function __construct(
        private UserLoginValidationInterface $userLoginValidation,
        private UserBusinessFacadeInterface $userBusinessFacade,
        private SessionHandlerInterface $sessionHandler
    ) {
    }

    public function execute(UserLoginDto $userLoginDto): ?ErrorsDTO
    {
        $errorDTO = $this->userLoginValidation->userLoginGetErrorsDTO($userLoginDto);

        if ($this->userLoginValidation->validateNoErrors($errorDTO)) {
            $userDTO = $this->userBusinessFacade->getUserByMail($userLoginDto->email);
            $this->sessionHandler->startSession($userDTO);
            return null;
        }
        return $errorDTO;
    }
}