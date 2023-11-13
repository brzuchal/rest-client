<?php

declare(strict_types=1);

namespace Brzuchal\RestClient;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

use function assert;
use function sprintf;
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
            ->info('REST Client configuration')
            ->fixXmlConfig('client')
            ->children()
                ->arrayNode('clients')
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array');
        assert($clientNode instanceof ArrayNodeDefinition);

        $clientNode
            ->fixXmlConfig('accept')
            ->children()
                ->scalarNode('http_client')
                    ->info('Symfony HttpClient service id, leave to null or skip if one should be created adhoc')
                ->end()
                ->scalarNode('serializer')
                    ->info('Symfony Serializer service id, leave to null or skip if one should be created adhoc')
                ->end()
                ->scalarNode('base_uri')
                    ->info('The URI to resolve relative URLs, following rules in RFC 3985, section 2.')
                ->end()
                ->arrayNode('default_headers')
                    ->info('Associative array of default headers: header => value(s).')
                    ->defaultValue([])
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->normalizeKeys(false)
                    ->variablePrototype()->end()
                ->end()
            ->end()
        ->end();
    }

    /** @param Config $config */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        foreach ($config['clients'] as $name => $values) {
            $id = 'rest_client.' . $name;
            if ($builder->has($id)) {
                throw new InvalidArgumentException(sprintf('Invalid client name: "%s" is reserved.', $id));
            }

            $baseUriParam = $id . '.base_uri';
            $container->parameters()->set($baseUriParam, $values['base_uri']);
            $httpClient    = ! empty($values['http_client']) ? new Reference($values['http_client']) : null;
            $serializer    = ! empty($values['serializer']) ? new Reference($values['serializer']) : null;
            $clientService = $container->services()->set($id, RestClientInterface::class);

            $builderId      = $id . '.builder';
            $builderService = $container->services()->set($builderId, RestClientBuilderInterface::class);
            $builderService->factory([RestClient::class, 'builder'])
                ->arg(0, $httpClient)
                ->arg(1, $serializer);

            if (! empty($values['base_uri'])) {
                $builderService->call('baseUrl', [param($baseUriParam)]);
            }

            if (empty($values['default_headers'])) {
                $clientService->factory([RestClient::class, 'create'])
                    ->arg(0, param($baseUriParam))
                    ->arg(1, $httpClient)
                    ->arg(2, $serializer);
                continue;
            }

            $builderService->call('defaultHeaders', [$values['default_headers']]);
            $clientService->factory([new Reference($builderId), 'build']);
        }
    }
}
