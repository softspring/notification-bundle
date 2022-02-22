<?php

namespace Softspring\NotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SfsNotificationExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $container->setParameter('sfs_notification.model.notification.class', $config['notification_class']);
        $container->setParameter('sfs_notification.model.user.class', $config['user_class']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config'));

        $loader->load('services.yaml');

        if ('custom' !== $config['db_driver']) {
            $container->setParameter($this->getAlias().'.backend_type_'.$config['db_driver'], true);
        }

        if (true === $config['notify_user_command']) {
            $loader->load('services/notify_user_command.yaml');
        }
    }
}
