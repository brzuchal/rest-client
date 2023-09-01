<?php declare(strict_types=1);

namespace Brzuchal\RestClient\Tests;

use Brzuchal\RestClient\RestClientBundle;
use Brzuchal\RestClient\RestClient;
use Brzuchal\RestClient\RestClientInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpKernel\DependencyInjection\MergeExtensionConfigurationPass;

class RestClientBundleTest extends TestCase
{
//    public function testRestClientBuilderDefinition(): void
//    {
//        $container = self::getContainerBuilder();
//
//        $this->assertTrue($container->hasDefinition('rest_client.builder'));
//        $definition = $container->getDefinition('rest_client.builder');
//        $this->assertEquals(DefaultRestClientBuilder::class, $definition->getClass());
//        $this->assertEquals([RestClient::class, 'builder'], $definition->getFactory());
//    }

    public function testNamedRestClientDefinition(): void
    {
        $container = self::getContainerBuilder([
            'clients' => [
                'test' => ['base_uri' => 'https://example.com/todos'],
            ],
        ]);

        $this->assertTrue($container->hasParameter('rest_client.test.base_uri'));
        $this->assertEquals('https://example.com/todos', $container->getParameter('rest_client.test.base_uri'));

        $this->assertTrue($container->hasDefinition('rest_client.test'));
        $definition = $container->getDefinition('rest_client.test');
        $this->assertEquals(RestClientInterface::class, $definition->getClass());
        $this->assertEquals([RestClient::class, 'create'], $definition->getFactory());
        $this->assertEquals('%rest_client.test.base_uri%', $definition->getArgument(0));
    }

    protected static function getContainerBuilder(array $config = []): ContainerBuilder
    {
        $bundle = new RestClientBundle();

        $container = new ContainerBuilder(new ParameterBag([
            'kernel.environment' => 'test',
            'kernel.build_dir' => sys_get_temp_dir(),
        ]));
        $container->registerExtension($bundle->getContainerExtension());
        $container->loadFromExtension('rest_client', $config);

        $configPass = new MergeExtensionConfigurationPass(['rest_client']);
        $configPass->process($container);

        return $container;
    }
}
