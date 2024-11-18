<?php

namespace App\Core\User\Application\Command\CreateUser;

use App\Core\User\Domain\Exception\UserAlreadyExistsException;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\User\Domain\User;
use App\Core\User\Domain\ValueObject\Email;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateUserHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $email = new Email($command->email);

        if ($this->userRepository->checkIfUserExists($command->email)) {
            throw new UserAlreadyExistsException('Użytkownik o podanym adresie email już istnieje');
        }

        $this->userRepository->save(new User(
            email: $email,
            active: $command->active
        ));

        $this->userRepository->flush();
    }
}
