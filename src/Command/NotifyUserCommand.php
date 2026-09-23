<?php

namespace Softspring\NotificationBundle\Command;

use Doctrine\Persistence\ManagerRegistry;
use Softspring\NotificationBundle\Notifier\Notifier;
use Softspring\UserBundle\Model\UserInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class NotifyUserCommand extends Command
{
    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly Notifier $notifier,
        private readonly string $userClassName,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('sfs:notification:notify')
            ->setDescription('Notifies a user.')
            ->addOption('email', null, InputOption::VALUE_OPTIONAL, 'The email')
            ->addOption('username', null, InputOption::VALUE_OPTIONAL, 'The username')
            ->addArgument('message', InputArgument::REQUIRED, 'The notification message')
            ->setHelp(<<<'EOT'
The <info>sfs:notification:notify</info> command notifies a user with email matthieu@example.com:

  <info>php %command.full_name% --email=matthieu@example.com</info>
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $repo = $this->doctrine->getManager()->getRepository($this->userClassName);

        if ($email = $input->getOption('email')) {
            $user = $repo->findOneBy(['email' => $email]);
        } elseif ($username = $input->getOption('username')) {
            $user = $repo->findOneBy(['username' => $username]);
        } else {
            throw new InvalidOptionException('Email or username options is required');
        }

        $message = $input->getArgument('message');

        if (!$user instanceof UserInterface) {
            throw new InvalidOptionException('User not found');
        }

        $this->notifier->notifyUser($user, ['raw' => $message]);

        return Command::SUCCESS;
    }
}
