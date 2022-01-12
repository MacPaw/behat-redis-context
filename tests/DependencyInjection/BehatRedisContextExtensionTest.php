<?php

declare(strict_types=1);

namespace BehatRedisContext\Tests\DependencyInjection;

use BehatRedisContext\Context\RedisContext;
use BehatRedisContext\Context\RedisFixturesContext;
use BehatRedisContext\DependencyInjection\BehatRedisContextExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class BehatRedisContextExtensionTest extends TestCase
{
    public function testHasServices(): void
    {
        $extension = new BehatRedisContextExtension();
        $container = new ContainerBuilder();

        $this->assertInstanceOf(Extension::class, $extension);

        $extension->load([['dataFixturesPath' => 'src/DataFixture']], $container);

        $this->assertTrue($container->has(RedisContext::class));
        $this->assertTrue($container->has(RedisFixturesContext::class));
    }
}
