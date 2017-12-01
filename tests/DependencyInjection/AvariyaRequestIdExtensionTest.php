<?php

namespace Tests\Avariya\RequestIdBundle\DependencyInjection;

use Avariya\RequestIdBundle\DependencyInjection\AvariyaRequestIdExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class AvariyaRequestIdExtensionTest extends TestCase
{
    /**
     * @dataProvider loadConfigurationProvider
     *
     * @param array $services
     */
    public function testLoadConfiguration($services, $params)
    {
        $container = $this->createContainer();
        $container->registerExtension(new AvariyaRequestIdExtension());
        $container->loadFromExtension('avariya_request_id', []);
        $this->compileContainer($container);

        foreach ($services as $service) {
            static::assertTrue(
                $container->hasDefinition($service),
                $service . ' has no definition'
            );
        }

        foreach ($params as $param) {
            static::assertTrue(
                $container->hasParameter($param),
                'Parameter ' . $param . ' not found'
            );
        }
    }

    public function loadConfigurationProvider()
    {
        return [
            [
                [
                    'avariya.middleware.request_id',
                    'Avariya\RequestIdBundle\Listener\ExtendKernelListener',
                    'Qandidate\Stack\RequestId\MonologProcessor',
                    'Qandidate\Stack\UuidRequestIdGenerator',
                ],
                [
                    'avariya.request_id.header',
                ],
            ],
        ];
    }

    private function createContainer()
    {
        $container = new ContainerBuilder(new ParameterBag([
            'kernel.cache_dir' => __DIR__,
            'kernel.root_dir' => __DIR__.'/Fixtures',
            'kernel.charset' => 'UTF-8',
            'kernel.debug' => true,
            'kernel.bundles' => [
                'AvariyaRequestIdBundle' => 'Avariya\\RequestIdBundle\\AvariyaRequestIdBundle',
                'CsaGuzzleBundle' => 'Csa\\Bundle\\GuzzleBundle\\CsaGuzzleBundle',
            ],
        ]));
        return $container;
    }

    private function compileContainer(ContainerBuilder $container)
    {
        $container->getCompilerPassConfig()->setOptimizationPasses([]);
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->prependExtensionConfig('printdeal_product_content', [
            'guzzle_client' => [
                'host' => 'host',
                'version' => 'v1',
                'username' => 'username',
                'secret' => 'secret',
                'cache_handler_id' => 'cache_handler_id',
                'log_handler_id' => 'log_handler_id',
                'timeout' => 15,
                'connect_timeout' => 2,
            ]
        ]);
        $container->compile();
    }
}
