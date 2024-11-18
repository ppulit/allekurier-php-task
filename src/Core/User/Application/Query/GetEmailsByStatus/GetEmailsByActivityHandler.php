<?php

namespace App\Core\User\Application\Query\GetEmailsByStatus;

use App\Core\User\Application\DTO\UserDTO;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\User\Domain\User;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetEmailsByActivityHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(GetEmailsByActivityQuery $query): array
    {
        $users = $this->userRepository->getByActivity(
            $query->isActive
        );

        return array_map(function (User $user) {
            return new UserDTO(
                $user->getId(),
                $user->getEmail()
            );
        }, $users);
    }
}
