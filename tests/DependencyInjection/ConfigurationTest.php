<?php

namespace Tests\Avariya\RequestIdBundle\DependencyInjection;

use Avariya\RequestIdBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    /**
     * @dataProvider configurationProvider
     *
     * @param array $input
     * @param array $output
     */
    public function testConfiguration($input, $output)
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), [$input]);

        static::assertEquals(
            $config,
            $output
        );
    }

    public function configurationProvider()
    {
        return [
            [
                [
                ],
                [
                    'monolog_support' => true,
                    'kernel_subscriber' => true,
                    'header' => 'X-Request-Id',
                    'guzzle_middleware' => [
                        'enabled' => true,
                        'guzzle_tag' => 'csa_guzzle.middleware'
                    ]
                ],
            ],
            [
                [
                    'monolog_support' => false,
                    'kernel_subscriber' => false,
                    'guzzle_middleware' => false
                ],
                [
                    'monolog_support' => false,
                    'kernel_subscriber' => false,
                    'header' => 'X-Request-Id',
                    'guzzle_middleware' => [
                        'enabled' => false,
                        'guzzle_tag' => 'csa_guzzle.middleware'
                    ]
                ],
            ],
            [
                [
                    'monolog_support' => false,
                    'kernel_subscriber' => true,
                    'header' => 'Some-Header',
                    'guzzle_middleware' => false
                ],
                [
                    'monolog_support' => false,
                    'kernel_subscriber' => true,
                    'header' => 'Some-Header',
                    'guzzle_middleware' => [
                        'enabled' => false,
                        'guzzle_tag' => 'csa_guzzle.middleware'
                    ]
                ],
            ],
        ];
    }
}
