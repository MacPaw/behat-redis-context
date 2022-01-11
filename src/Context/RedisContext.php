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
    private ClientInterface $redis;

    public function __construct(ClientInterface $redis)
    {
        $this->redis = $redis;
    }

    /**
     * Before Scenario.
     *
     * @BeforeScenario
     */
    public function beforeScenario(): void
    {
        $this->redis->flushdb();
    }

    /**
     * After Feature.
     *
     * @AfterFeature
     */
    public static function afterFeature(): void
    {
        gc_collect_cycles();
    }

    /**
     * iSaveStringParamsToRedis.
     *
     * @param string $value
     * @param string $key
     *
     * @When /^I save string value "([^"]*)" to redis by "([^"]*)"$/
     */
    public function iSaveStringParamsToRedis(string $value, string $key): void
    {
        $this->redis->set($key, $value);
    }

    /**
     * iSeeInRedisValueByKay.
     *
     * @param string $value
     * @param string $key
     *
     * @throws InvalidArgumentException
     *
     * @When /^I see in redis value "([^"]*)" by key "([^"]*)"$/
     */
    public function iSeeInRedisValueByKay(string $value, string $key): void
    {
        $found = $this->redis->get($key);

        if (!$found) {
            throw new InvalidArgumentException(sprintf('In Redis does not data in key "%s"', $key));
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
     * Verifies that response should be exactly the same JSON as we expected.
     *
     * @param string       $key
     * @param PyStringNode $string
     *
     * @throws JsonException
     * @throws RuntimeException
     *
     * @Then /^I see in redis array by key "([^"]*)":$/
     */
    public function iSeeInRedisArrayByKey(string $key, PyStringNode $string): void
    {
        $actualResponse = $this->redis->hgetall($key);
        $expectedResponse = json_decode(trim($string->getRaw()), true, 512, JSON_THROW_ON_ERROR);

        if (array_diff($actualResponse, $expectedResponse)) {
            $prettyJSON = json_encode($actualResponse, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT, 512);
            $message = sprintf("Expected JSON does not match actual JSON:\n%s\n", $prettyJSON);

            throw new RuntimeException($message);
        }
    }
}
