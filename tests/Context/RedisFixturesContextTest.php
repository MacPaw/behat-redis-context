<?php

declare(strict_types=1);

namespace BehatRedisContext\Tests\Context;

use BehatRedisContext\Context\RedisFixturesContext;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Predis\Client;

class RedisFixturesContextTest extends TestCase
{
    public function testServiceNotFound(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The "Test" redis fixture not found.');

        $redisFixtureContext = new RedisFixturesContext(new Client());

        $redisFixtureContext->loadRedisFixtures('Test');
    }

    public function testInvalidFixtureFileThrowsException(): void
    {
        $tmpDir = sys_get_temp_dir() . '/behat_redis_test_' . uniqid();
        mkdir($tmpDir, 0777, true);
        file_put_contents($tmpDir . '/scalar.yml', 'just a string');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('fixture file must contain a YAML array');

        try {
            $redisFixtureContext = new RedisFixturesContext(new Client(), $tmpDir);
            $redisFixtureContext->loadRedisFixtures('scalar');
        } finally {
            unlink($tmpDir . '/scalar.yml');
            rmdir($tmpDir);
        }
    }
}
