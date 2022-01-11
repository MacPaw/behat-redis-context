<?php

declare(strict_types=1);

namespace BehatRedisContext\Context;

use Behat\Behat\Context\Context;
use InvalidArgumentException;
use Predis\ClientInterface;
use Symfony\Component\Yaml\Yaml;

class RedisFixturesContext implements Context
{
    private ClientInterface $redis;
    private string $dataFixturesPath;
    
    public function __construct(
        ClientInterface $redis,
        string $dataFixturesPath = ''
    ) {
        $this->redis = $redis;
        $this->dataFixturesPath = $dataFixturesPath;
    }
    
    /**
     * I load fixtures.
     *
     * @param string $aliases
     *
     * @throws InvalidArgumentException
     *
     * @Given /^I load redis fixtures "([^\"]*)"$/
     */
    public function loadRedisFixtures(string $aliases): void
    {
        $aliases = array_map('trim', explode(',', $aliases));
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
    
    private function loadFixtures(array $fixtures): void
    {
        foreach ($fixtures as $fixture) {
            $this->loadFile(Yaml::parseFile($fixture));
        }
    }
    
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
