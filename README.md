# composer.json fixer

[![Latest Stable Version](https://img.shields.io/packagist/v/kubawerlos/composer-json-fixer.svg)](https://packagist.org/packages/kubawerlos/composer-json-fixer)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D5.6.6-8892BF.svg)](https://php.net)
[![License](https://img.shields.io/github/license/kubawerlos/composer-json-fixer.svg)](https://packagist.org/packages/kubawerlos/composer-json-fixer)
[![Build Status](https://img.shields.io/travis/kubawerlos/composer-json-fixer/master.svg)](https://travis-ci.org/kubawerlos/composer-json-fixer)

A tool for fixing and cleaning up `composer.json` file
according to its [schema](https://getcomposer.org/doc/04-schema.md) and best practices.

## Installation
composer.json fixer can be installed [globally](https://getcomposer.org/doc/03-cli.md#global):
```bash
composer global require kubawerlos/composer-json-fixer
```
or as a dependency (e.g. to include into CI process):
```bash
composer require --dev kubawerlos/composer-json-fixer
```

## Usage
Run and fix:
```bash
vendor/bin/composer-json-fixer
```
See diff of potential fixes:
```bash
vendor/bin/composer-json-fixer --dry-run
```
Update dependencies with `composer require`:
```bash
vendor/bin/composer-json-fixer --with-updates
```


## Exit status
 - `0` - `composer.json` file does not require fixing,
 - `1` - `composer.json` file can be, or was fixed,
 - `2` - exception was thrown.


## Fixers
- **composer keys lowercase** - changes all properties names to lower case
- **autoload** - fixes paths and sorts `autoload` and `autoload-dev`
- **license** - adds `license` if it is missing
- **lowercase** - makes package name lowe case
- **remove default minimum stability** - removes `minimum-stability` if it has default value ("stable")
- **repositories** - sorts `repositories`
- **require** - cleans up versions for `require` and `require-dev`
- **sorting by key** - sorts `config` values by key
- **sorting by value** - sorts `keywords`
- **unwanted property** - removes `version` if it is present
- **composer keys sorting** - sorts properties according to [the documentation](https://getcomposer.org/doc/04-schema.md)


## Contribute
Request a feature or report a bug by creating [issue](https://github.com/kubawerlos/composer-json-fixer/issues).

Or fork the repo, develop your changes, make sure all checks pass:
```bash
vendor/bin/phpcs --report-full --standard=PSR2 src tests
vendor/bin/php-cs-fixer fix --config=tests/php-cs-fixer.config.php --diff --dry-run
vendor/bin/phpunit -c tests/phpunit.xml
```
and submit a pull request.
