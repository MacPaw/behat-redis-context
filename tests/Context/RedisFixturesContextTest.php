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
}
