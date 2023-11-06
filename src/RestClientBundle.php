<?php

declare(strict_types=1);

namespace Brzuchal\RestClient;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

use function assert;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

/**
 * Symfony Bundle.
 *
 * @psalm-type ConfigClient = array{base_uri?:string,accept?:string,default_headers?:array}
 * @psalm-type Config = array{clients:array<non-empty-string,ConfigClient>}
 */
final class RestClientBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $clientNode = $definition->rootNode()
            ->fixXmlConfig('client')
            ->children()
                ->arrayNode('clients')
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array');
        assert($clientNode instanceof ArrayNodeDefinition);

        $clientNode
            ->children()
                ->scalarNode('base_uri')->end()
                ->scalarNode('accept')->defaultValue('application/json')->end()
                ->variableNode('default_headers')->defaultNull()->end()
            ->end()
        ->end();
    }

    /** @param Config $config */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        foreach ($config['clients'] as $name => $values) {
            $id = 'rest_client.' . $name;
            $container->parameters()->set($id . '.base_uri', $values['base_uri']);
            $container->services()->set($id, RestClientInterface::class)
                ->factory([RestClient::class, 'create'])
                ->arg(0, param($id . '.base_uri'));
        }
    }
}
