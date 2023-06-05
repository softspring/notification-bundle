<?php

namespace Softspring\NotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('sfs_notification');
        $rootNode = $treeBuilder->getRootNode();

        $supportedDrivers = ['orm', 'custom'];

        $rootNode
            ->children()
                ->scalarNode('notification_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('user_class')->isRequired()->cannotBeEmpty()->end()
                ->booleanNode('notify_user_command')->defaultFalse()->end()
                ->scalarNode('db_driver')
                    ->validate()
                    ->ifNotInArray($supportedDrivers)
                        ->thenInvalid('The driver %s is not supported. Please choose one of '.json_encode($supportedDrivers))
                    ->end()
                    ->cannotBeOverwritten()
                    ->cannotBeEmpty()
                    ->defaultValue('orm')
                ->end()
            ->end()
        ;

        //    notifications:
        //        notif1:
        //            event: "issue.create"
        //            filter_expression: "isGranted(...)"
        //            message:
        //                raw: "raw message"
        //                translation_id: "message.id"
        //                translation_domain: "notifications"
        //                data: []
        //            code: 500
        //            screen: true
        //            email: false
        //            push: true

        return $treeBuilder;
    }
}
