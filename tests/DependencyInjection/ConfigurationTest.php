<?php

declare(strict_types=1);

namespace BehatRedisContext\Tests\DependencyInjection;

use BehatRedisContext\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class ConfigurationTest extends TestCase
{
    public function testConfiguration(): void
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $this->assertInstanceOf(ConfigurationInterface::class, $configuration);

        $configs = $processor->processConfiguration($configuration, []);

        $this->assertSame([], $configs);
    }

    public function testConfigurationAddFixturePatch(): void
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $this->assertInstanceOf(ConfigurationInterface::class, $configuration);

        $configs = $processor->processConfiguration($configuration, [['dataFixturesPath' => '/src/DataFixture']]);

        $this->assertSame(['dataFixturesPath' => '/src/DataFixture'], $configs);
    }
}
