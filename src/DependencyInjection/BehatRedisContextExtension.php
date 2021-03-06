<?php

declare(strict_types=1);

namespace BehatRedisContext\DependencyInjection;

use BehatRedisContext\Context\RedisFixturesContext;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class BehatRedisContextExtension extends Extension
{
    /**
     * @param array<array> $configs
     *
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $this->loadBehatDatabaseContext($config, $loader, $container);
    }

    /**
     * @param array<array> $config
     */
    private function loadBehatDatabaseContext(
        array $config,
        XmlFileLoader $loader,
        ContainerBuilder $container
    ): void {
        $loader->load('context.xml');

        if ($container->has(RedisFixturesContext::class)) {
            $databaseContextDefinition = $container->findDefinition(RedisFixturesContext::class);
            $databaseContextDefinition->setArgument('$dataFixturesPath', $config['dataFixturesPath']);
        }
    }
}
