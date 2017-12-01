<?php

namespace Avariya\RequestIdBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    const REQUEST_ID_HEADER = 'X-Request-Id';

    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('avariya_request_id');

        $rootNode
            ->children()
                ->booleanNode('monolog_support')
                    ->defaultTrue()
                ->end()
            ->end()
            ->children()
                    ->booleanNode('kernel_subscriber')
                    ->defaultTrue()
                ->end()
            ->end()
            ->children()
                ->scalarNode('header')
                    ->defaultValue(self::REQUEST_ID_HEADER)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
