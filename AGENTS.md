# AGENTS.md

## Cursor Cloud specific instructions

This is a PHP Composer library (Symfony bundle) — not a standalone app. No services need to run.

### Dev commands

All commands are defined in `composer.json` scripts section:

| Command | Purpose |
|---------|---------|
| `composer phpunit` | Run PHPUnit test suite (4 tests) |
| `composer phpstan` | Run PHPStan static analysis (level max) |
| `composer code-style` | Run PHP CodeSniffer (PSR-12 + Slevomat rules) |
| `composer code-style-fix` | Auto-fix code style violations |
| `composer dev-checks` | Run all checks (validate + phpstan + phpunit + code-style) |
| `composer validate` | Validate composer.json |

### Known issues

- PHPStan has a pre-existing baseline mismatch: the ignored error pattern for `NodeDefinition::children()` in `phpstan-baseline.neon` no longer matches with current Symfony versions. This causes `composer phpstan` to exit with code 1. This is a repo issue, not an environment issue.
- The `checkMissingIterableValueType` config option in `phpstan.neon.dist` is deprecated in newer PHPStan versions.

### Environment notes

- PHP 8.2 is installed from `ppa:ondrej/php` with extensions: xml, mbstring, curl, zip.
- Composer is installed at `/usr/local/bin/composer`.
- No Redis server needed — existing unit tests use Predis pure-PHP client without connecting to Redis.
- No Docker, databases, or external services required.
