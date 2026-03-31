<?php

declare(strict_types=1);

namespace BehatRedisContext\Tests\Context;

use Behat\Gherkin\Node\PyStringNode;
use BehatRedisContext\Context\RedisContext;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Predis\ClientInterface;
use RuntimeException;

final class RedisContextTest extends TestCase
{
    private ClientInterface&MockObject $redis;

    protected function setUp(): void
    {
        parent::setUp();
        $this->redis = $this->createMock(ClientInterface::class);
    }

    /**
     * @param array<string, mixed> $returns Command name => return value for single-arg calls
     */
    private function stubRedisCalls(array $returns): void
    {
        $this->redis->method('__call')->willReturnCallback(
            static function (string $command, array $arguments) use ($returns) {
                $key = $arguments[0] ?? null;
                if ($command === 'flushdb') {
                    return null;
                }

                if ($command === 'get' && is_string($key)) {
                    return $returns['get:' . $key] ?? null;
                }

                if ($command === 'hgetall' && is_string($key)) {
                    return $returns['hgetall:' . $key] ?? [];
                }

                return null;
            }
        );
    }

    public function testBeforeScenarioFlushesDatabase(): void
    {
        $this->redis->expects($this->once())->method('__call')->with('flushdb', []);

        $context = new RedisContext($this->redis);
        $context->beforeScenario();
    }

    public function testISeeInRedisValueByKeyWhenMatching(): void
    {
        $this->expectNotToPerformAssertions();
        $this->stubRedisCalls(['get:k' => 'v']);

        $context = new RedisContext($this->redis);
        $context->iSeeInRedisValueByKey('v', 'k');
    }

    public function testISeeInRedisValueByKeyWhenMissing(): void
    {
        $this->stubRedisCalls(['get:k' => null]);

        $this->expectException(InvalidArgumentException::class);

        $context = new RedisContext($this->redis);
        $context->iSeeInRedisValueByKey('v', 'k');
    }

    public function testISeeInRedisValueByKeyWhenMismatch(): void
    {
        $this->stubRedisCalls(['get:k' => 'other']);

        $this->expectException(InvalidArgumentException::class);

        $context = new RedisContext($this->redis);
        $context->iSeeInRedisValueByKey('v', 'k');
    }

    public function testIDontSeeInRedisKeyWhenEmpty(): void
    {
        $this->expectNotToPerformAssertions();
        $this->stubRedisCalls(['get:k' => null]);

        $context = new RedisContext($this->redis);
        $context->iDontSeeInRedisKey('k');
    }

    public function testIDontSeeInRedisKeyWhenPresent(): void
    {
        $this->stubRedisCalls(['get:k' => 'x']);

        $this->expectException(InvalidArgumentException::class);

        $context = new RedisContext($this->redis);
        $context->iDontSeeInRedisKey('k');
    }

    public function testISeeInRedisArrayByKeyWhenMatching(): void
    {
        $this->expectNotToPerformAssertions();
        $this->stubRedisCalls(['hgetall:h' => ['a' => '1', 'b' => '2']]);
        $py = new PyStringNode(['{"a":"1","b":"2"}'], 1);

        $context = new RedisContext($this->redis);
        $context->iSeeInRedisArrayByKey('h', $py);
    }

    public function testISeeInRedisArrayByKeyWhenMismatch(): void
    {
        $this->stubRedisCalls(['hgetall:h' => ['a' => '9']]);
        $py = new PyStringNode(['{"a":"1"}'], 1);

        $this->expectException(RuntimeException::class);

        $context = new RedisContext($this->redis);
        $context->iSeeInRedisArrayByKey('h', $py);
    }

    public function testISeeInRedisArrayByKeyWhenJsonNotObjectArray(): void
    {
        $this->stubRedisCalls(['hgetall:h' => []]);
        $py = new PyStringNode(['null'], 1);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Expected JSON object for Redis hash comparison.');

        $context = new RedisContext($this->redis);
        $context->iSeeInRedisArrayByKey('h', $py);
    }

    public function testISeeInRedisArrayByKeyWhenNonScalarInHash(): void
    {
        $this->stubRedisCalls(['hgetall:h' => ['x' => ['nested']]]);
        $py = new PyStringNode(['{"x":"y"}'], 1);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Expected scalar values in Redis hash comparison.');

        $context = new RedisContext($this->redis);
        $context->iSeeInRedisArrayByKey('h', $py);
    }

    public function testISeeInRedisAnyValueByKeyWhenPresent(): void
    {
        $this->expectNotToPerformAssertions();
        $this->stubRedisCalls(['get:k' => 'anything']);

        $context = new RedisContext($this->redis);
        $context->iSeeInRedisAnyValueByKey('k');
    }

    public function testISeeInRedisAnyValueByKeyWhenMissing(): void
    {
        $this->stubRedisCalls(['get:k' => null]);

        $this->expectException(InvalidArgumentException::class);

        $context = new RedisContext($this->redis);
        $context->iSeeInRedisAnyValueByKey('k');
    }

    public function testISeeInRedisSerializedValueByKeyWhenMatching(): void
    {
        $this->expectNotToPerformAssertions();
        $this->stubRedisCalls(['get:k' => serialize('payload')]);

        $context = new RedisContext($this->redis);
        $context->iSeeInRedisSerializedValueByKey('payload', 'k');
    }

    public function testISeeInRedisSerializedValueByKeyWhenUnserializedNotString(): void
    {
        $this->stubRedisCalls(['get:k' => serialize(12345)]);

        $this->expectException(InvalidArgumentException::class);

        $context = new RedisContext($this->redis);
        $context->iSeeInRedisSerializedValueByKey('x', 'k');
    }

    public function testISeeInRedisSerializedValueByKeyWhenMismatch(): void
    {
        $this->stubRedisCalls(['get:k' => serialize('a')]);

        $this->expectException(InvalidArgumentException::class);

        $context = new RedisContext($this->redis);
        $context->iSeeInRedisSerializedValueByKey('b', 'k');
    }

    public function testGetRedisStringValueRejectsNonStringRedisResponse(): void
    {
        $this->redis->method('__call')->willReturnCallback(
            static function (string $command, array $arguments) {
                if ($command === 'get') {
                    return 99;
                }

                return null;
            }
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unexpected Redis response');

        $context = new RedisContext($this->redis);
        $context->iSeeInRedisValueByKey('v', 'k');
    }
}
