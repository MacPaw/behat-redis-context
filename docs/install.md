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