<?php

namespace ComposerJsonFixer\Command;

use ComposerJsonFixer\FixerFactory;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReadmeCommand extends BaseCommand
{
    const SHIELDS_HOST  = 'https://img.shields.io';
    const PACKAGIST_URL = 'https://packagist.org/packages/kubawerlos/composer-json-fixer';
    const TRAVIS_URL    = 'https://travis-ci.org/kubawerlos/composer-json-fixer';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->header());
        $output->writeln($this->fixers());
        $output->writeln($this->footer());
    }

    /**
     * @return string
     */
    private function header()
    {
        $composer = \json_decode(\file_get_contents(__DIR__ . '/../../composer.json'));

        return \sprintf(
            '# composer.json fixer

[![Latest Stable Version](%s/packagist/v/kubawerlos/composer-json-fixer.svg)](%s)
[![PHP Version](%s/badge/php-%s-8892BF.svg)](https://php.net)
[![License](%s/github/license/kubawerlos/composer-json-fixer.svg)](%s)
[![Build Status](%s/travis/kubawerlos/composer-json-fixer/master.svg)](%s)

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
',
            self::SHIELDS_HOST,
            self::PACKAGIST_URL,
            self::SHIELDS_HOST,
            \rawurlencode($composer->require->php),
            self::SHIELDS_HOST,
            self::PACKAGIST_URL,
            self::SHIELDS_HOST,
            self::TRAVIS_URL
        );
    }

    /**
     * @return string
     */
    private function fixers()
    {
        $output = "\n## Fixers\n";

        $fixerFactory = new FixerFactory();

        foreach ($fixerFactory->fixers() as $fixer) {
            $reflection = new \ReflectionClass($fixer);
            $fixerName  = \preg_replace('/^(.*)Fixer$/', '$1', $reflection->getShortName());
            $fixerName  = \preg_replace('/(?<!^)[A-Z]/', ' $0', $fixerName);
            $fixerName  = \mb_strtolower($fixerName);
            $output .= \sprintf(
                "- **%s** - %s\n",
                $fixerName,
                $fixer->description()
            );
        }

        return $output . "\n";
    }

    /**
     * @return string
     */
    private function footer()
    {
        return '## Contribute
Request a feature or report a bug by creating [issue](https://github.com/kubawerlos/composer-json-fixer/issues).

Or fork the repo, develop your changes, make sure all checks pass:
```bash
vendor/bin/phpcs --report-full --standard=PSR2 src tests
vendor/bin/php-cs-fixer fix --config=tests/php-cs-fixer.config.php --diff --dry-run
vendor/bin/phpunit -c tests/phpunit.xml
```
and submit a pull request.';
    }
}
