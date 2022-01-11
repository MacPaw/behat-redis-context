<?php

declare(strict_types=1);

namespace BehatRedisContext\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

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

        $this->loadDatabaseHelper($loader);
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

        if ($container->findDefinition('behat_redis_context.redis_fixture_context')) {
            $databaseContextDefinition = $container->findDefinition('behat_redis_context.redis_fixture_context');
            $databaseContextDefinition->setArgument('$dataFixturesPath', $config['dataFixturesPath']);
        }
    }

    private function loadDatabaseHelper(
        XmlFileLoader $loader
    ): void {
        $loader->load('context.xml');
    }
}
