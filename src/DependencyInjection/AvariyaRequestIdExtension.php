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

    /**
     * @inheritDoc
     */
    protected function loadInternal(array $configuration, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(self::CONFIGS_PATH));

        $loader->load('qandidate_stack.yaml');

        if ($configuration['monolog_support']) {
            $loader->load('monolog.yaml');
        }

        if ($configuration['kernel_subscriber']) {
            $loader->load('kernel.yaml');
        }

        if ($configuration['guzzle_middleware']['enabled']) {
            $loader->load('guzzle_middleware.yaml');

            if (isset($configuration['guzzle_middleware']['guzzle_tag'])) {
                $container->getDefinition('avariya.middleware.request_id')
                    ->addTag(
                        $configuration['guzzle_middleware']['guzzle_tag'],
                        [
                            'alias' => 'request_id_middleware',
                        ]
                    );
            }
        }

        $container->setParameter('avariya.request_id.header', (string)$configuration['header']);
    }
}
