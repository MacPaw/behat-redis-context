<?php

declare(strict_types=1);

namespace BehatRedisContext\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('behat_redis_context');
        $root = $treeBuilder->getRootNode()->children();

        $this->addDataFixturesPathSection($root);

        return $treeBuilder;
    }

    private function addDataFixturesPathSection(NodeBuilder $builder): void
    {
        $builder->scalarNode('dataFixturesPath')->cannotBeEmpty()->end();
    }
}
