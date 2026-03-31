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

### Notes

- PHPStan 1.x is currently used; an upgrade to 2.x is available but not yet applied.

### Environment notes

- PHP 8.2 is installed from `ppa:ondrej/php` with extensions: xml, mbstring, curl, zip.
- Composer is installed at `/usr/local/bin/composer`.
- No Redis server needed — existing unit tests use Predis pure-PHP client without connecting to Redis.
- No Docker, databases, or external services required.
