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
    private const NAME = 'composer.json fixer';

    private const SHIELDS_HOST = 'https://img.shields.io';

    protected function execute(InputInterface $input, OutputInterface $output) : void
    {
        $output->writeln(\sprintf('# %s', self::NAME));
        $output->writeln($this->badges());
        $output->writeln($this->description());
        $output->writeln($this->installation());
        $output->writeln($this->usage());
        $output->writeln($this->fixers());
        $output->writeln($this->example());
        $output->writeln($this->exitStatus());
        $output->writeln($this->contributing());
    }

    private function badges() : string
    {
        return "\n" . \implode("\n", [
            $this->badge(
                'Latest Stable Version',
                \sprintf('%s/packagist/v/%s.svg', self::SHIELDS_HOST, $this->composer()->name),
                \sprintf('https://packagist.org/packages/%s', $this->composer()->name)
            ),
            $this->badge(
                'PHP Version',
                \sprintf('%s/badge/php-%s-8892BF.svg', self::SHIELDS_HOST, \rawurlencode($this->composer()->require->php)),
                'https://php.net'
            ),
            $this->badge(
                'License',
                \sprintf('%s/github/license/%s.svg', self::SHIELDS_HOST, $this->composer()->name),
                \sprintf('https://packagist.org/packages/%s', $this->composer()->name)
            ),
            $this->badge(
                'Build Status',
                \sprintf('%s/travis/%s/master.svg', self::SHIELDS_HOST, $this->composer()->name),
                \sprintf('https://travis-ci.org/%s', $this->composer()->name)
            ),
            $this->badge(
                'Code coverage',
                \sprintf('https://coveralls.io/repos/github/%s/badge.svg?branch=master', $this->composer()->name),
                \sprintf('https://coveralls.io/github/%s?branch=master', $this->composer()->name)
            ),
        ]) . "\n";
    }

    private function badge(string $description, string $imageUrl, string $targetUrl) : string
    {
        return
            \sprintf(
                '[![%s](%s)](%s)',
                $description,
                $imageUrl,
                $targetUrl
            );
    }

    private function description() : string
    {
        return '
A tool for fixing and cleaning up `composer.json` file
according to its [schema](https://getcomposer.org/doc/04-schema.md) and best practices.
';
    }

    private function installation() : string
    {
        return \sprintf(
            '
## Installation
%s can be installed [globally](https://getcomposer.org/doc/03-cli.md#global):
```bash
composer global require %s
```
or as a dependency (e.g. to include into CI process):
```bash
composer require --dev %s
```
',
            self::NAME,
            $this->composer()->name,
            $this->composer()->name
        );
    }

    private function usage() : string
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

    private function fixers() : string
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

    private function example() : string
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

    private function exitStatus() : string
    {
        return '
## Exit status
 - `0` - `composer.json` file does not require fixing,
 - `1` - `composer.json` file can be, or was fixed,
 - `2` - exception was thrown.
';
    }

    private function contributing() : string
    {
        return \sprintf(
            '
## Contributing
Request a feature or report a bug by creating [issue](https://github.com/kubawerlos/composer-json-fixer/issues).

Alternatively, fork the repo, develop your changes, regenerate `README.md`:
```bash
src/Readme/run > README.md
```
make sure all checks pass:
```bash
%s
```
and submit a pull request.',
            \implode("\n", $this->travisScripts())
        );
    }

    private function travisScripts() : array
    {
        $yaml = Yaml::parse(\file_get_contents(__DIR__ . '/../../.travis.yml'));

        $scripts = \array_filter(
            $yaml['script'],
            static function ($script) {
                return \mb_strpos($script, 'vendor/bin') === 0;
            }
        );

        return \array_map(
            static function (string $script) : string {
                return \rtrim($script, ' $COVERAGE');
            },
            $scripts
        );
    }

    private function composer() : \stdClass
    {
        return \json_decode(\file_get_contents(__DIR__ . '/../../composer.json'));
    }
}
