<?php

declare(strict_types = 1);

namespace Tests\Command;

use ComposerJsonFixer\Command\FixerCommand;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

/**
 * @covers \ComposerJsonFixer\Command\FixerCommand
 */
final class FixerCommandTest extends TestCase
{
    /** @var vfsStreamDirectory */
    private $directory;

    /** @var ApplicationTester */
    private $tester;

    protected function setUp() : void
    {
        $this->directory = vfsStream::setup();

        $application = new Application();
        $command     = new FixerCommand('composer-json-fixer');

        $application->add($command);
        $application->setDefaultCommand($command->getName(), true);
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $this->tester = new ApplicationTester($application);
    }

    public function testImpossibleToDryRunAndWithUpdate() : void
    {
        $this->tester->run([
            '--dry-run'      => true,
            '--with-updates' => true,
            'directory'      => $this->directory->url(),
        ]);

        static::assertSame(2, $this->tester->getStatusCode());
        static::assertContains(
            'It is impossible to run with both "--dry-run" and "--with-updates"',
            $this->tester->getDisplay()
        );
    }

    public function testIncorrectPath() : void
    {
        $path = $this->directory->url() . '/incorrect/path';

        $this->tester->run([
            'directory' => $path,
        ]);

        static::assertSame(2, $this->tester->getStatusCode());
        static::assertContains(\sprintf('The "%s" directory does not exist', $path), $this->tester->getDisplay());
    }

    public function testDirectoryWithoutComposerJson() : void
    {
        $this->tester->run([
            'directory' => $this->directory->url(),
        ]);

        static::assertSame(2, $this->tester->getStatusCode());
        static::assertContains('File "composer.json" not found', $this->tester->getDisplay());
    }

    public function testInvalidFile() : void
    {
        vfsStream::newFile('composer.json')
            ->at($this->directory)
            ->setContent('}');

        $this->tester->run([
            'directory' => $this->directory->url(),
        ]);

        static::assertSame(2, $this->tester->getStatusCode());
        static::assertContains('File "composer.json" does not contain valid JSON', $this->tester->getDisplay());
    }

    public function testDryRunNotChangingFile() : void
    {
        $composerJson = vfsStream::newFile('composer.json')
            ->at($this->directory)
            ->setContent('{"NaMe":"Foo"}');

        $this->tester->run([
            '--dry-run' => true,
            'directory' => $this->directory->url(),
        ]);

        static::assertSame(1, $this->tester->getStatusCode());
        static::assertSame('{"NaMe":"Foo"}', $composerJson->getContent());
        static::assertContains('"name": "foo', $this->tester->getDisplay());
    }

    public function testFixing() : void
    {
        $composerJson = vfsStream::newFile('composer.json')
            ->at($this->directory)
            ->setContent('{"NaMe":"Foo"}');

        $this->tester->run([
            'directory' => $this->directory->url(),
        ]);

        static::assertSame(1, $this->tester->getStatusCode());
        static::assertContains('"name": "foo', $composerJson->getContent());
    }

    public function testSelfComposer() : void
    {
        $this->tester->run([
            'directory' => __DIR__ . '/../..',
        ]);

        static::assertSame(0, $this->tester->getStatusCode());
    }

    public function testFixingWithUpdateAndFakeRepository() : void
    {
        vfsStream::newFile('composer.json')
            ->at($this->directory)
            ->setContent('{"require":{"foo/bar": "^1.0"}}');

        $this->tester->run([
            '--with-updates' => true,
            'directory'      => $this->directory->url(),
        ]);

        static::assertSame(2, $this->tester->getStatusCode());
        static::assertContains('Command "composer require" failed', $this->tester->getDisplay());
    }

    public function testWithUpdateWhenNothingToFixAndUpdate() : void
    {
        vfsStream::newFile('composer.json')
            ->at($this->directory)
            ->setContent("{\n    \"license\": \"proprietary\"\n}\n");

        $this->tester->run([
            '--with-updates' => true,
            'directory'      => $this->directory->url(),
        ]);

        static::assertSame(0, $this->tester->getStatusCode());
        static::assertContains('There is nothing to fix', $this->tester->getDisplay());
    }
}
