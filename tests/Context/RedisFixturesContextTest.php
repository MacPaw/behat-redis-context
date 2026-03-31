<?php

declare(strict_types=1);

namespace BehatRedisContext\Tests\Context;

use BehatRedisContext\Context\RedisFixturesContext;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Predis\Client;
use Predis\ClientInterface;

final class RedisFixturesContextTest extends TestCase
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

    public function testLoadsStringAndHashFixtures(): void
    {
        $tmpDir = sys_get_temp_dir() . '/behat_redis_test_' . uniqid();
        mkdir($tmpDir, 0777, true);
        file_put_contents(
            $tmpDir . '/data.yml',
            <<<YAML
simple: value
hash:
  f1: v1
  f2: v2
YAML
        );

        $redis = $this->createMock(ClientInterface::class);
        $redis->expects($this->exactly(2))->method('__call')->willReturnCallback(
            static function (string $command, array $arguments): void {
                if ($command === 'set') {
                    self::assertSame(['simple', 'value'], $arguments);

                    return;
                }

                if ($command === 'hmset') {
                    self::assertSame('hash', $arguments[0]);
                    self::assertSame(['f1' => 'v1', 'f2' => 'v2'], $arguments[1]);

                    return;
                }

                self::fail('Unexpected command: ' . $command);
            }
        );

        try {
            $context = new RedisFixturesContext($redis, $tmpDir);
            $context->loadRedisFixtures('data');
        } finally {
            unlink($tmpDir . '/data.yml');
            rmdir($tmpDir);
        }
    }

    public function testInvalidFixtureKeyType(): void
    {
        $tmpDir = sys_get_temp_dir() . '/behat_redis_test_' . uniqid();
        mkdir($tmpDir, 0777, true);
        file_put_contents($tmpDir . '/bad.yml', "0: not a map key\n");

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Fixture keys must be strings.');

        try {
            $context = new RedisFixturesContext(new Client(), $tmpDir);
            $context->loadRedisFixtures('bad');
        } finally {
            unlink($tmpDir . '/bad.yml');
            rmdir($tmpDir);
        }
    }

    public function testInvalidFixtureValueType(): void
    {
        $tmpDir = sys_get_temp_dir() . '/behat_redis_test_' . uniqid();
        mkdir($tmpDir, 0777, true);
        file_put_contents($tmpDir . '/bad.yml', "key: 123\n");

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid value type for fixture key "key".');

        try {
            $context = new RedisFixturesContext(new Client(), $tmpDir);
            $context->loadRedisFixtures('bad');
        } finally {
            unlink($tmpDir . '/bad.yml');
            rmdir($tmpDir);
        }
    }

    public function testInvalidHashEntry(): void
    {
        $tmpDir = sys_get_temp_dir() . '/behat_redis_test_' . uniqid();
        mkdir($tmpDir, 0777, true);
        file_put_contents($tmpDir . '/bad.yml', "h:\n  0: x\n");

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid hash entry for fixture key "h".');

        try {
            $context = new RedisFixturesContext(new Client(), $tmpDir);
            $context->loadRedisFixtures('bad');
        } finally {
            unlink($tmpDir . '/bad.yml');
            rmdir($tmpDir);
        }
    }
}
