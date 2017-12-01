<?php

namespace Avariya\RequestIdBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
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
                    ->defaultValue(true)
                ->end()
            ->end()
            ->children()
                ->booleanNode('kernel_subscriber')
                    ->defaultValue(true)
                ->end()
            ->end()
        ;
        //@todo: add guzzle is active and tag

        return $treeBuilder;
    }
}
