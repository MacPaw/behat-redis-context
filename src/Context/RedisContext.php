<?php

declare(strict_types=1);

namespace BehatRedisContext\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use InvalidArgumentException;
use JsonException;
use Predis\ClientInterface;
use RuntimeException;

class RedisContext implements Context
{
    public function __construct(private readonly ClientInterface $redis)
    {
    }

    /**
     * @BeforeScenario
     */
    public function beforeScenario(): void
    {
        $this->redis->flushdb();
    }

    /**
     * @AfterFeature
     */
    public static function afterFeature(): void
    {
        gc_collect_cycles();
    }

    /**
     *
     * @When /^I save string value "([^"]*)" to redis by "([^"]*)"$/
     */
    public function iSaveStringParamsToRedis(string $value, string $key): void
    {
        $this->redis->set($key, $value);
    }

    /**
     * @When /^I save serialized value "([^"]*)" to redis by "([^"]*)"$/
     */
    public function iSaveSerializedParamsToRedis(string $value, string $key): void
    {
        $this->redis->set($key, serialize($value));
    }

    /**
     *
     * @throws InvalidArgumentException
     *
     * @When /^I see in redis value "([^"]*)" by key "([^"]*)"$/
     */
    public function iSeeInRedisValueByKey(string $value, string $key): void
    {
        $found = $this->getRedisStringValue($key);

        if ($found === null || $found === '') {
            throw new InvalidArgumentException(sprintf('In Redis does not exist data for key "%s"', $key));
        }

        if ($value !== $found) {
            throw new InvalidArgumentException(sprintf(
                'Value in key "%s" do not match "%s" actual "%s"',
                $key,
                $value,
                $found
            ));
        }
    }

    /**
     * @When /^I don't see in redis key "([^"]*)"$/
     */
    public function iDontSeeInRedisKey(string $key): void
    {
        $found = $this->getRedisStringValue($key);

        if ($found !== null && $found !== '') {
            throw new InvalidArgumentException(sprintf('Redis contains data for key "%s"', $key));
        }
    }

    /**
     *
     * @throws JsonException
     * @throws RuntimeException
     *
     * @Then /^I see in redis array by key "([^"]*)":$/
     */
    public function iSeeInRedisArrayByKey(string $key, PyStringNode $string): void
    {
        $actualRaw = $this->redis->hgetall($key);
        $decoded = json_decode(trim($string->getRaw()), true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($decoded)) {
            throw new RuntimeException('Expected JSON object for Redis hash comparison.');
        }

        $actualResponse = array_map([self::class, 'stringifyForComparison'], $actualRaw);
        $expectedResponse = array_map([self::class, 'stringifyForComparison'], $decoded);

        if (array_diff($actualResponse, $expectedResponse)) {
            $prettyJSON = json_encode($actualResponse, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT, 512);
            $message = sprintf("Expected JSON does not match actual JSON:\n%s\n", $prettyJSON);

            throw new RuntimeException($message);
        }
    }

    private static function stringifyForComparison(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        throw new RuntimeException('Expected scalar values in Redis hash comparison.');
    }

    /**
     * @throws InvalidArgumentException
     *
     * @When /^I see in redis any value by key "([^"]*)"$/
     */
    public function iSeeInRedisAnyValueByKey(string $key): void
    {
        $found = $this->getRedisStringValue($key);
        if ($found === null) {
            throw new InvalidArgumentException(sprintf('In Redis does not exist data for key "%s"', $key));
        }
    }

    /**
     * @When /^I see in redis serialized value "([^"]*)" by key "([^"]*)"$/
     */
    public function iSeeInRedisSerializedValueByKey(string $value, string $key): void
    {
        $found = $this->getRedisStringValue($key);

        if ($found === null) {
            throw new InvalidArgumentException(sprintf('In Redis does not exist data for key "%s"', $key));
        }

        $unserialized = unserialize($found);
        if (!is_string($unserialized)) {
            throw new InvalidArgumentException(sprintf('In Redis does not exist data for key "%s"', $key));
        }

        if ($value !== $unserialized) {
            throw new InvalidArgumentException(sprintf(
                'Value in key "%s" do not match "%s" actual "%s"',
                $key,
                $value,
                $unserialized,
            ));
        }
    }

    /**
     * Predis 1.x documents {@see ClientInterface::get()} as `string`; newer releases use `string|null`.
     */
    private function getRedisStringValue(string $key): ?string
    {
        $value = $this->redis->get($key);

        if ($value === null || $value === false) {
            return null;
        }

        if (!is_string($value)) {
            throw new InvalidArgumentException(sprintf('Unexpected Redis response for key "%s".', $key));
        }

        return $value;
    }
}
