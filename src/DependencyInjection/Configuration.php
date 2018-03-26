<?php

namespace Avariya\RequestIdBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->root('avariya_request_id');

        $rootNode
            ->children()
                ->booleanNode('monolog_support')
                    ->defaultTrue()
                ->end()
                    ->booleanNode('kernel_subscriber')
                    ->defaultTrue()
                ->end()
                ->scalarNode('header')
                    ->defaultValue(self::REQUEST_ID_HEADER)
                ->end()
                ->arrayNode('guzzle_middleware')
                    ->addDefaultsIfNotSet()
                    ->treatTrueLike(['enabled' => true])
                    ->treatFalseLike(['enabled' => false])
                    ->children()
                        ->booleanNode('enabled')->defaultTrue()->end()
                        ->scalarNode('guzzle_tag')->defaultValue('csa_guzzle.middleware')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
