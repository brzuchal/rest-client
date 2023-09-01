<?php declare(strict_types=1);

namespace Brzuchal\RestClient;

use Composer\InstalledVersions;
use Composer\Semver\VersionParser;
use LogicException;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

if (!InstalledVersions::satisfies(new VersionParser(), 'symfony/framework-bundle', '^6.3')) {
    throw new LogicException(
        'Symfony Framework is missing or does not satisfy ^6.3 constraint. ' .
        'Try running "composer require symfony/framework-bundle:^6.3".',
    );
}

/**
 * Symfony Bundle.
 *
 * @author Michał Brzuchalski <michal.brzuchalski@gmail.com>
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

    /**
     * @param Config $config
     * @param ContainerConfigurator $container
     * @param ContainerBuilder $builder
     */
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
