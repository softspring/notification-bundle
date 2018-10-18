<?php

namespace Softspring\NotificationBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class NotificationMailerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('Softspring\NotificationBundle\Mailer\NotificationMailer')) {
            return;
        }

        $definition = $container->getDefinition('Softspring\NotificationBundle\Mailer\NotificationMailer');
        $definition->setArgument(0, new Reference($container->getParameter('sfs_notification.mailer.service')));
    }
}