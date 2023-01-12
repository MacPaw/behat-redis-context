Symfony Behat Redis Context
=================================

| Version | Build Status | Code Coverage |
|:---------:|:-------------:|:-----:|
| `main`| [![CI][main Build Status Image]][main Build Status] | [![Coverage Status][main Code Coverage Image]][main Code Coverage] |
| `develop`| [![CI][develop Build Status Image]][develop Build Status] | [![Coverage Status][develop Code Coverage Image]][develop Code Coverage] |

Installation
============

Step 1: Download the Bundle
----------------------------------
Open a command console, enter your project directory and execute:

###  Applications that use Symfony Flex [in progress](https://github.com/MacPaw/BehatRedisContext/issues/2)

```console
$ composer require --dev macpaw/behat-redis-context
```

### Applications that don't use Symfony Flex

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require --dev macpaw/behat-redis-context
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.


Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            BehatRedisContextBundle\BehatRedisContextBundle::class => ['test' => true],
        );

        // ...
    }

    // ...
}
```

Create configuration for behat redis context:

`config/packages/test/behat_redis_context.yaml `
```yaml
behat_redis_context:
    dataFixturesPath: ""
```


Step 2: Change path to directory with your fixtures
----------------------------------
`config/packages/test/behat_redis_context.yaml `
```yaml
behat_redis_context:
    dataFixturesPath: "your path"
```

Step 3: Change path to directory with your fixtures
----------------------------------
`config/services_test.yaml`
```yaml
Predis\ClientInterface: 'Your Redis Client'
```

Example if you use [Symfony Redis Bundle](https://github.com/symfony-bundles/redis-bundle):
```yaml
Predis\ClientInterface: '@SymfonyBundles\RedisBundle\Redis\ClientInterface'
```

Step 4: Configure Behat
=============
Go to `behat.yml`

```yaml
...
  contexts:
    - BehatRedisContext\Context\RedisContext
    - BehatRedisContext\Context\RedisFixturesContext
...
```

[main Build Status]: https://github.com/macpaw/BehatRedisContext/actions?query=workflow%3ACI+branch%3Amain
[main Build Status Image]: https://github.com/macpaw/BehatRedisContext/workflows/CI/badge.svg?branch=main
[develop Build Status]: https://github.com/macpaw/BehatRedisContext/actions?query=workflow%3ACI+branch%3Adevelop
[develop Build Status Image]: https://github.com/macpaw/BehatRedisContext/workflows/CI/badge.svg?branch=develop
[main Code Coverage]: https://codecov.io/gh/macpaw/behat-redis-context/branch/main
[main Code Coverage Image]: https://img.shields.io/codecov/c/github/macpaw/behat-redis-context/main?logo=codecov
[develop Code Coverage]: https://codecov.io/gh/macpaw/behat-redis-context/branch/develop
[develop Code Coverage Image]: https://img.shields.io/codecov/c/github/macpaw/behat-redis-context/develop?logo=codecov
