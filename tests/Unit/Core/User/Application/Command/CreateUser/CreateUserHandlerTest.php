<?php

namespace App\Tests\Unit\Core\User\Application\Command\CreateUser;

use App\Core\User\Application\Command\CreateUser\CreateUserCommand;
use App\Core\User\Application\Command\CreateUser\CreateUserHandler;
use App\Core\User\Domain\Exception\InvalidEmailException;
use App\Core\User\Domain\Exception\UserAlreadyExistsException;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\User\Domain\User;
use App\Core\User\Domain\ValueObject\Email;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateUserHandlerTest extends TestCase
{
    private UserRepositoryInterface|MockObject $userRepository;

    private CreateUserHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->handler = new CreateUserHandler(
            $this->userRepository = $this->createMock(
                UserRepositoryInterface::class
            )
        );
    }

    public function test_handle_success(): void
    {
        $user = new User(
            email: new Email('test@test.pl'),
            active: false
        );

        $this->userRepository->expects(self::once())
            ->method('save')
            ->with($user);

        $this->userRepository->expects(self::once())
            ->method('flush');

        $this->handler->__invoke(new CreateUserCommand('test@test.pl', false));
    }

    public function test_handle_empty_email(): void
    {
        $this->expectException(InvalidEmailException::class);

        $this->handler->__invoke(new CreateUserCommand('', false));
    }

    public function test_handle_invalid_email(): void
    {
        $this->expectException(InvalidEmailException::class);

        $this->handler->__invoke(new CreateUserCommand('bezmalpy.pl', false));
    }

    public function test_handle_too_long_email(): void
    {
        $this->expectException(InvalidEmailException::class);

        $email = str_repeat('a', 301).'@test.pl';
        $this->handler->__invoke(new CreateUserCommand($email, false));
    }

    public function test_handle_user_already_exists(): void
    {
        $this->userRepository->expects(self::once())
            ->method('checkIfUserExists')
            ->willReturn(true);

        $this->expectException(UserAlreadyExistsException::class);

        $this->handler->__invoke(new CreateUserCommand('test@test.pl', false));
    }

}
