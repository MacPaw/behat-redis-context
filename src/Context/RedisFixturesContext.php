<?php

declare(strict_types=1);

namespace BehatRedisContext\Context;

use Behat\Behat\Context\Context;
use InvalidArgumentException;
use Predis\ClientInterface;
use Symfony\Component\Yaml\Yaml;

class RedisFixturesContext implements Context
{
    public function __construct(private readonly ClientInterface $redis, private readonly string $dataFixturesPath = '')
    {
    }

    /**
     *
     * @throws InvalidArgumentException
     * @Given /^I load redis fixtures "([^\"]*)"$/
     */
    public function loadRedisFixtures(string $aliases): void
    {
        $aliases = array_map(trim(...), explode(',', $aliases));
        $fixtures = [];

        foreach ($aliases as $alias) {
            $fixture = sprintf('%s/%s.yml', $this->dataFixturesPath, $alias);

            if (!is_file($fixture)) {
                throw new InvalidArgumentException(sprintf('The "%s" redis fixture not found.', $alias));
            }

            $fixtures[] = $fixture;
        }

        $this->loadFixtures($fixtures);
    }

    /**
     * @param array<string> $fixtures
     */
    private function loadFixtures(array $fixtures): void
    {
        foreach ($fixtures as $fixture) {
            $parsed = Yaml::parseFile($fixture);

            if (!is_array($parsed)) {
                throw new InvalidArgumentException(
                    sprintf('The "%s" fixture file must contain a YAML array.', $fixture)
                );
            }

            $this->loadFile($this->normalizeFixturePayload($parsed));
        }
    }

    /**
     * @param array<mixed> $parsed
     *
     * @return array<string, string|array<string, string>>
     */
    private function normalizeFixturePayload(array $parsed): array
    {
        $normalized = [];

        foreach ($parsed as $key => $value) {
            if (!is_string($key)) {
                throw new InvalidArgumentException('Fixture keys must be strings.');
            }

            if (is_string($value)) {
                $normalized[$key] = $value;

                continue;
            }

            if (!is_array($value)) {
                throw new InvalidArgumentException(sprintf('Invalid value type for fixture key "%s".', $key));
            }

            $hash = [];
            foreach ($value as $field => $fieldValue) {
                if (!is_string($field) || !is_string($fieldValue)) {
                    throw new InvalidArgumentException(sprintf('Invalid hash entry for fixture key "%s".', $key));
                }

                $hash[$field] = $fieldValue;
            }

            $normalized[$key] = $hash;
        }

        return $normalized;
    }

    /**
     * @param array<string, string|array<string, string>> $params
     */
    private function loadFile(array $params): void
    {
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $this->redis->hmset($key, $value);
            } else {
                $this->redis->set($key, $value);
            }
        }
    }
}
