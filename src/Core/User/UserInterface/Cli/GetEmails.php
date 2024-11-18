<?php

namespace App\Core\User\UserInterface\Cli;

use App\Common\Bus\QueryBusInterface;
use App\Core\User\Application\DTO\UserDTO;
use App\Core\User\Application\Query\GetEmailsByStatus\GetEmailsByActivityQuery;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:user:get-emails-for-inactive',
    description: 'Pobieranie listy emaili dla nieaktywnych użytkowników'
)]
class GetEmails extends Command
{
    public function __construct(private readonly QueryBusInterface $bus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->bus->dispatch(new GetEmailsByActivityQuery(
            false
        ));

        if (empty($users)) {
            $output->writeln('Brak nieaktywnych użytkowników');

            return Command::SUCCESS;
        }

        $output->writeln(sprintf('Znaleziono %d nieaktywnych użytkowników:', count($users)));

        /** @var UserDTO $user */
        foreach ($users as $user) {
            $output->writeln($user->email);
        }

        return Command::SUCCESS;
    }
}
