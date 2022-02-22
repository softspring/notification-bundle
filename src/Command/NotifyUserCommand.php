<?php

namespace Softspring\NotificationBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\User\UserInterface;

class NotifyUserCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected function configure()
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
        $userClassName = $this->container->getParameter('sfs_notification.model.user.class');
        $em = $this->container->get('doctrine')->getManager();

        $repo = $em->getRepository($userClassName);

        if ($email = $input->getOption('email')) {
            $user = $repo->findOneBy(['email' => $email]);
        } elseif ($username = $input->getOption('username')) {
            $user = $repo->findOneBy(['username' => $username]);
        } else {
            throw new InvalidOptionException('Email or username options is required');
        }

        if (!$user) {
            throw new InvalidOptionException('User not found');
        }

        $notifier = $this->container->get('sfs_notifier');

        $message = $input->getArgument('message');

        /* @var UserInterface $user */
        $notifier->notifyUser($user, ['raw' => $message]);

        return 0;
    }
}
