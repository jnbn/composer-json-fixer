<?php

declare(strict_types = 1);

namespace ComposerJsonFixer\Readme;

use ComposerJsonFixer\Command\FixerCommand;
use ComposerJsonFixer\FixerFactory;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\Yaml\Yaml;

class ReadmeCommand extends BaseCommand
{
    private const SHIELDS_HOST  = 'https://img.shields.io';
    private const PACKAGIST_URL = 'https://packagist.org/packages/kubawerlos/composer-json-fixer';
    private const TRAVIS_URL    = 'https://travis-ci.org/kubawerlos/composer-json-fixer';

    protected function execute(InputInterface $input, OutputInterface $output) : void
    {
        $output->writeln('# composer.json fixer');
        $output->writeln($this->badges());
        $output->writeln($this->description());
        $output->writeln($this->installation());
        $output->writeln($this->usage());
        $output->writeln($this->fixers());
        $output->writeln($this->example());
        $output->writeln($this->exitStatus());
        $output->writeln($this->contributing());
    }

    /**
     * @return string
     */
    private function badges()
    {
        $composer = \json_decode(\file_get_contents(__DIR__ . '/../../composer.json'));

        return \sprintf(
            '
[![Latest Stable Version](%s/packagist/v/kubawerlos/composer-json-fixer.svg)](%s)
[![PHP Version](%s/badge/php-%s-8892BF.svg)](https://php.net)
[![License](%s/github/license/kubawerlos/composer-json-fixer.svg)](%s)
[![Build Status](%s/travis/kubawerlos/composer-json-fixer/master.svg)](%s)
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
    private function description()
    {
        return '
A tool for fixing and cleaning up `composer.json` file
according to its [schema](https://getcomposer.org/doc/04-schema.md) and best practices.
';
    }

    /**
     * @return string
     */
    private function installation()
    {
        return '
## Installation
composer.json fixer can be installed [globally](https://getcomposer.org/doc/03-cli.md#global):
```bash
composer global require kubawerlos/composer-json-fixer
```
or as a dependency (e.g. to include into CI process):
```bash
composer require --dev kubawerlos/composer-json-fixer
```
';
    }

    /**
     * @return string
     */
    private function usage()
    {
        return '
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
';
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

        return $output;
    }

    /**
     * @return string
     */
    private function example()
    {
        $application = new Application();
        $command     = new FixerCommand('composer-json-fixer');

        $application->add($command);
        $application->setDefaultCommand($command->getName(), true);
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $tester = new ApplicationTester($application);

        $jsonBeforeFixing = \file_get_contents(__DIR__ . '/example.json');

        $directory    = vfsStream::setup();
        $composerJson = vfsStream::newFile('composer.json')
            ->at($directory)
            ->setContent($jsonBeforeFixing);

        $tester->run(['directory' => $directory->url()]);

        return \sprintf(
            '
## Example
Before running `composer-json-fixer`:
```json
%s
```
After:
```json
%s
```
',
            $jsonBeforeFixing,
            $composerJson->getContent()
        );
    }

    /**
     * @return string
     */
    private function exitStatus()
    {
        return '
## Exit status
 - `0` - `composer.json` file does not require fixing,
 - `1` - `composer.json` file can be, or was fixed,
 - `2` - exception was thrown.
';
    }

    /**
     * @return string
     */
    private function contributing()
    {
        return \sprintf(
            '
## Contributing
Request a feature or report a bug by creating [issue](https://github.com/kubawerlos/composer-json-fixer/issues).

Alternatively, fork the repo, develop your changes, regenerate `README.md`:
```bash
src/Readme/bin > README.md
```
make sure all checks pass:
```bash
%s
```
and submit a pull request.',
            \implode("\n", $this->travisScripts())
        );
    }

    /**
     * @return array
     */
    private function travisScripts()
    {
        $yaml = Yaml::parse(\file_get_contents(__DIR__ . '/../../.travis.yml'));

        return \array_filter(
            $yaml['script'],
            static function ($script) {
                return \mb_strpos($script, 'vendor/bin') === 0;
            }
        );
    }
}
