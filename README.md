# Symfony Behat Redis Context
| Version | Build Status | Code Coverage |
|:---------:|:-------------:|:-----:|
| `main`| [![CI][main Build Status Image]][main Build Status] | [![Coverage Status][main Code Coverage Image]][main Code Coverage] |
| `develop`| [![CI][develop Build Status Image]][develop Build Status] | [![Coverage Status][develop Code Coverage Image]][develop Code Coverage] |

Symfony Behat Redis Context is a package that integrates Redis operations with Behat for behavior-driven development (BDD). This context allows you to store, retrieve, and validate data in Redis as part of your Behat testing scenarios. It's useful when testing applications that depend on Redis for caching, session storage, or data management.

This documentation provides step-by-step guides for installing the package and utilizing each Redis-related step within Behat scenarios.

## How to Install Symfony Behat Redis Context

To install Symfony Behat Redis Context, follow these steps:

1. Add the package to your project using composer:
   ```bash
   composer require --dev macpaw/behat-redis-context

For detailed steps and configuration, refer to the [Installation Steps](docs/install.md)

## RedisContext Documentation

Below are the available Redis operations that you can use in your Behat tests. Each step integrates seamlessly with Redis to ensure data is stored, retrieved, or validated as expected.

### Redis Step Definitions:

* [Check Any Value by Redis Key](docs/RedisContext/check-any-value-by-key.md)  
  Verifies if any value is stored in Redis under a specific key.

* [Check Array Value Stored in Redis](docs/RedisContext/check-array.md)  
  Ensures that the stored array or hash in Redis matches the expected structure.

* [Check if Key Exists in Redis](docs/RedisContext/check-key-exist.md)  
  Checks whether a specific key exists in Redis.

* [Check Serialized Value in Redis](docs/RedisContext/check-serialized-value.md)  
  Verifies that a serialized value stored in Redis matches the expected serialized value.

* [Check String Value in Redis](docs/RedisContext/check-value-in-redis.md)  
  Validates if a string value in Redis matches the expected value.

* [Clean Redis Database in Test](docs/RedisContext/clean-db.md)  
  Automatically flushes the Redis database before running a scenario to ensure a clean state.

* [Store Serialized Value in Redis](docs/RedisContext/store-seralized-value.md)  
  Serializes and stores a value in Redis with a given key.

* [Store String Value in Redis](docs/RedisContext/store-string-value.md)  
  Stores a simple string value in Redis under the specified key.

## RedisFixtureContext Documentation

Here you can find detailed documentation about using Redis fixtures in Behat:

1. **[How It Works](docs/RedisFixtures/how-works.md)**  
   Learn about the inner workings of the RedisFixtureContext and how it integrates with your Behat tests.

2. **[How to Load Fixture Data into Redis](docs/RedisFixtures/how-load-fixture-in-redis.md)**  
   A step-by-step guide on how to load predefined data fixtures into Redis using YAML files in Behat.

3. **[Handling Missing Fixture Files](docs/RedisFixtures/handling-missing-fixtures.md)**  
   What to do when a specified fixture file is missing and how to handle such errors in your tests.

[main Build Status]: https://github.com/macpaw/BehatRedisContext/actions?query=workflow%3ACI+branch%3Amain
[main Build Status Image]: https://github.com/macpaw/BehatRedisContext/workflows/CI/badge.svg?branch=main
[develop Build Status]: https://github.com/macpaw/BehatRedisContext/actions?query=workflow%3ACI+branch%3Adevelop
[develop Build Status Image]: https://github.com/macpaw/BehatRedisContext/workflows/CI/badge.svg?branch=develop
[main Code Coverage]: https://codecov.io/gh/macpaw/behat-redis-context/branch/main
[main Code Coverage Image]: https://img.shields.io/codecov/c/github/macpaw/behat-redis-context/main?logo=codecov
[develop Code Coverage]: https://codecov.io/gh/macpaw/behat-redis-context/branch/develop
[develop Code Coverage Image]: https://img.shields.io/codecov/c/github/macpaw/behat-redis-context/develop?logo=codecov
