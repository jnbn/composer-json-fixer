<?php

namespace ComposerJsonFixer\Command;

use ComposerJsonFixer\FixerFactory;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReadmeCommand extends BaseCommand
{
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

[![Latest Stable Version](https://img.shields.io/packagist/v/kubawerlos/composer-json-fixer.svg)](https://packagist.org/packages/kubawerlos/composer-json-fixer)
[![PHP Version](https://img.shields.io/badge/php-%s-8892BF.svg)](https://php.net)
[![License](https://img.shields.io/github/license/kubawerlos/composer-json-fixer.svg)](https://packagist.org/packages/kubawerlos/composer-json-fixer)
[![Build Status](https://img.shields.io/travis/kubawerlos/composer-json-fixer/master.svg)](https://travis-ci.org/kubawerlos/composer-json-fixer)

A tool for fixing and cleaning up `composer.json` file according to its [schema](https://getcomposer.org/doc/04-schema.md) and best practices.

## Installation
composer.json fixer can be installed [globally](https://getcomposer.org/doc/03-cli.md#global):
```bash
composer global require kubawerlos/composer-json-fixer
```
or as developing dependency (e.g. to include into CI process):
```bash
composer require --dev kubawerlos/composer-json-fixer
```

## Usage
See diff of potential fixes:
```bash
vendor/bin/composer-json-fixer --dry-run
```
or also update dependencies with `composer require`:
```bash
vendor/bin/composer-json-fixer --with-updates
```


## Exit status
 - `0` - `composer.json` file does not require fixing,
 - `1` - `composer.json` file can be, or was fixed,
 - `2` - exception was thrown.
',
            \rawurlencode($composer->require->php)
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
                "- **%s**\n",
                $fixerName
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
vendor/bin/php-cs-fixer fix --config=tests/.php-cs-fixer.config.php --diff --dry-run src tests
vendor/bin/phpunit -c tests/phpunit.xml
```
and submit a pull request.';
    }
}
