<?php

declare(strict_types = 1);

namespace Tests;

use ComposerJsonFixer\Command\FixerCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @coversNothing
 */
final class FunctionalTest extends TestCase
{
    private const TMP_DIRECTORY = __DIR__ . '/tmp';

    /** @var ApplicationTester */
    private $tester;

    protected function setUp() : void
    {
        $application = new Application();
        $command     = new FixerCommand('composer-json-fixer');

        $application->add($command);
        $application->setDefaultCommand($command->getName(), true);
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $this->tester = new ApplicationTester($application);

        $filesystem = new Filesystem();
        $filesystem->remove(self::TMP_DIRECTORY);
        $filesystem->mkdir(self::TMP_DIRECTORY);
    }

    protected function tearDown() : void
    {
        $filesystem = new Filesystem();
        $filesystem->remove(self::TMP_DIRECTORY);
    }

    /**
     * @dataProvider provideFixerCases
     */
    public function testFixer(
        array $options,
        int $exitCode,
        string $message,
        array $providedJson,
        array $expectedJson = null
    ) : void {
        \file_put_contents(
            self::TMP_DIRECTORY . '/composer.json',
            \json_encode($providedJson, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n"
        );

        $options['directory'] = self::TMP_DIRECTORY;

        $this->tester->run($options);

        static::assertSame($exitCode, $this->tester->getStatusCode());
        static::assertContains($message, $this->tester->getDisplay());

        if ($expectedJson !== null) {
            static::assertStringEqualsFile(
                self::TMP_DIRECTORY . '/composer.json',
                \json_encode($expectedJson, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n"
            );
        }
    }

    public function provideFixerCases() : array
    {
        return [
            'nothing to fix' => [
                [],
                0,
                'There is nothing to fix',
                [
                    'name'        => 'foo/bar',
                    'description' => 'text',
                    'license'     => 'proprietary',
                    'require'     => ['psr/log' => '^1.0'],
                    'require-dev' => ['psr/cache' => '^1.0'],
                ],
            ],
            'missing licence and dependency updated' => [
                [],
                1,
                'File "composer.json" was fixed successfully',
                [
                    'name'        => 'foo/bar',
                    'description' => 'text',
                    'require'     => ['psr/log' => '^1.0'],
                    'require-dev' => ['psr/cache' => '^1.0'],
                ],
                [
                    'name'        => 'foo/bar',
                    'description' => 'text',
                    'license'     => 'proprietary',
                    'require'     => ['psr/log' => '^1.0'],
                    'require-dev' => ['psr/cache' => '^1.0'],
                ],
            ],
            'non-existent repository' => [
                ['--upgrade' => true],
                2,
                'Command "composer require" failed',
                [
                    'name'        => 'foo/bar',
                    'description' => 'text',
                    'license'     => 'proprietary',
                    'require'     => ['vendor/repository' => '*'],
                ],
            ],
            'upgrade require and require-dev' => [
                ['--upgrade' => true],
                1,
                'File "composer.json" was fixed successfully',
                [
                    'name'        => 'foo/bar',
                    'description' => 'text',
                    'license'     => 'proprietary',
                    'require'     => ['psr/log' => '*'],
                    'require-dev' => ['psr/cache' => '*'],
                ],
                [
                    'name'        => 'foo/bar',
                    'description' => 'text',
                    'license'     => 'proprietary',
                    'require'     => ['psr/log' => '^1.0'],
                    'require-dev' => ['psr/cache' => '^1.0'],
                ],
            ],
            'upgrade only require-dev' => [
                ['--upgrade-dev' => true],
                1,
                'File "composer.json" was fixed successfully',
                [
                    'name'        => 'foo/bar',
                    'description' => 'text',
                    'license'     => 'proprietary',
                    'require'     => ['psr/log' => '*'],
                    'require-dev' => ['psr/cache' => '*'],
                ],
                [
                    'name'        => 'foo/bar',
                    'description' => 'text',
                    'license'     => 'proprietary',
                    'require'     => ['psr/log' => '*'],
                    'require-dev' => ['psr/cache' => '^1.0'],
                ],
            ],
        ];
    }
}
