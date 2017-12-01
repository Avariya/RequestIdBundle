<?php

namespace Avariya\RequestIdBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AvariyaRequestIdExtension extends ConfigurableExtension
{
    const CONFIGS_PATH = __DIR__.'/../Resources/config';
    const DEFAULT_GUZZLE_TIMEOUT = 15;
    const DEFAULT_GUZZLE_CONNECT_TIMEOUT = 2;

    /**
     * @inheritDoc
     */
    protected function loadInternal(array $configuration, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(self::CONFIGS_PATH));

        $loader->load('qandidate_stack.yaml');

        if ($configuration['monolog_support'] && $container->hasDefinition('monolog.logger')) {
            $loader->load('monolog.yaml');
        }

        if ($configuration['kernel_subscriber'] && $container->hasDefinition('kernel')) {
            $loader->load('kernel.yaml');
        }

        $container->setParameter('avariya.request_id.header', (string)$configuration['header']);

        $loader->load('csa_guzzle.yaml');

        var_dump($configuration);
    }
}
